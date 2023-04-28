<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NewIpAddressNotification;

class NotifyNewIpAddress
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
    public function handle(Authenticated $event): void
    {
        $user = $event->user;
        $ips = $user->ips;
        $currentIp = request()->ip();

        if (!in_array($currentIp, $ips)) {
            $ips[] = $currentIp;
            $user->ips = $ips;
            $user->notify(new NewIpAddressNotification($currentIp));
            $user->save();
        }
    }
}
