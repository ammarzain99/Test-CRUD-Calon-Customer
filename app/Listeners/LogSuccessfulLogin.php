<?php

namespace App\Listeners;

use App\Services\ActivityLogService;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSuccessfulLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        ActivityLogService::log(
            action: 'login',
            model: 'User',
            modelId: $event->user->id,
            old: null,
            new: [
                'email' => $event->user->email,
                'login_at' => now()->toDateTimeString(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]
        );
    }
}
