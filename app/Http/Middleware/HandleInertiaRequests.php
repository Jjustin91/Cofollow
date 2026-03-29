<?php

namespace App\Http\Middleware;

use App\Models\College;
use App\Models\Program;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return array_merge(parent::share($request), [
            // Add these two lines to share the data globally
            'colleges' => College::all(),
            'programs' => Program::all(),
            // Add this new line to pass unread notifications globally!
            'auth.notifications' => fn () => $request->user() ? $request->user()->unreadNotifications : [],
            'unreadNotifications' => fn () => $request->user() ? $request->user()->unreadNotifications : [],
        ]);
    }
}
