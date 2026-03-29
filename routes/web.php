<?php

use Illuminate\Foundation\Application;
use App\Models\Announcement;
use App\Notifications\CampusAlert;
use App\Models\Organization;
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request; // Add this line to handle the request properly
use App\Models\Task;
use Illuminate\Support\Facades\Notification;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Inject the Request object here
    // Dashboard Route
    Route::get('/dashboard', function (Request $request) {
        $user = $request->user();
        
        $eventsQuery = Event::with('college')->orderBy('event_date', 'asc');
        if ($user->college_id) {
            $eventsQuery->where('college_id', $user->college_id)->orWhereNull('college_id');
        }
        
        // Get the IDs of the orgs this user joined
        $myOrgIds = $user->organizations()->pluck('organizations.id');
        
        // Fetch the latest announcements from those specific orgs
        $announcements = Announcement::with(['organization', 'user'])
            ->whereIn('organization_id', $myOrgIds)
            ->latest()
            ->take(10)
            ->get();
        
        return Inertia::render('Dashboard', [
            'events' => $eventsQuery->take(5)->get(),
            'tasks' => $user->tasks()->orderBy('created_at', 'desc')->get(),
            'myOrgs' => $user->organizations()->get(),
            'announcements' => $announcements, // Pass to Vue!
        ]);
    })->name('dashboard');

    // Full Calendar View
    Route::get('/calendar', function (Request $request) {
        $user = $request->user();
        
        $eventsQuery = \App\Models\Event::with('college')->orderBy('event_date', 'asc');
        
        if ($user->college_id) {
            $eventsQuery->where('college_id', $user->college_id)
                        ->orWhereNull('college_id');
        }
        
        // Notice we don't use ->take(5) here, we get ALL of them!
        return Inertia::render('Calendar', [
            'events' => $eventsQuery->get() 
        ]);
    })->name('calendar');

    // Post a new announcement (Officers Only)
    Route::post('/announcements', function (Request $request) {
        $request->validate([
            'organization_id' => 'required|exists:organizations,id',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $user = $request->user();

        // SECURITY CHECK: Ensure this user is actually an officer of the selected org!
        $org = $user->organizations()
                    ->where('organizations.id', $request->organization_id)
                    ->wherePivot('role', 'officer')
                    ->firstOrFail();

        // 1. Create the announcement
        $announcement = Announcement::create([
            'organization_id' => $org->id,
            'user_id' => $user->id,
            'title' => $request->title,
            'content' => $request->content,
        ]);

        // 2. Fetch all members of this org EXCEPT the officer who just posted it
        $members = $org->users()->where('users.id', '!=', $user->id)->get();

        // 3. Blast the Smart Alert to everyone else!
        Notification::send($members, new CampusAlert($announcement));

        return back();
    })->name('announcements.store');

    // Mark a specific notification as read
    Route::post('/notifications/{id}/read', function (Request $request, $id) {
        $notification = $request->user()->notifications()->findOrFail($id);
        $notification->markAsRead();
        
        // Redirect them back so they stay on the same page
        return back(); 
    })->name('notifications.read');

    // Add a new task
    Route::post('/tasks', function (Request $request) {
        $request->validate(['title' => 'required|string|max:255']);
        $request->user()->tasks()->create(['title' => $request->title]);
        return back();
    })->name('tasks.store');

    // Toggle a task (complete/incomplete)
    Route::put('/tasks/{task}', function (Request $request, Task $task) {
        if ($request->user()->id !== $task->user_id) abort(403); // Security check
        $task->update(['is_completed' => $request->boolean('is_completed')]);
        return back();
    })->name('tasks.update');

    // View all organizations
    Route::get('/organizations', function (Request $request) {
        $user = $request->user();
        
        // Fetch orgs and attach a boolean checking if the current user is already a member
        $organizations = Organization::with('college')->get()->map(function ($org) use ($user) {
            $org->is_member = $org->users()->where('user_id', $user->id)->exists();
            return $org;
        });

        return Inertia::render('Organizations', [
            'organizations' => $organizations
        ]);
    })->name('organizations.index');

    // Toggle joining/leaving an organization
    Route::post('/organizations/{organization}/toggle', function (Request $request, Organization $organization) {
        // The toggle() method automatically adds the user if they aren't in the org, and removes them if they are!
        $request->user()->organizations()->toggle($organization->id);
        return back();
    })->name('organizations.toggle');

});