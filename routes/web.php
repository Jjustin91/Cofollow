<?php

use Illuminate\Foundation\Application;
use App\Models\Organization;
use App\Models\Event;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Http\Request; // Add this line to handle the request properly
use App\Models\Task;

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
        
        return Inertia::render('Dashboard', [
            'events' => $eventsQuery->take(5)->get(),
            'tasks' => $user->tasks()->orderBy('created_at', 'desc')->get(), // Pass the user's tasks
        ]);
    })->name('dashboard');

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