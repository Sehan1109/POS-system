<?php

namespace App\Listeners;

use App\Models\ActivityLog;
use Illuminate\Auth\Events\Logout;

class LogSuccessfulLogout
{
    public function handle(Logout $event): void
    {
        if ($event->user) {
            ActivityLog::create([
                'user_id'     => $event->user->id,
                'action'      => 'logout',
                'description' => "User '{$event->user->name}' logged out.",
                'ip_address'  => request()->ip(),
                'user_agent'  => request()->userAgent(),
            ]);
        }
    }
}
