<?php

namespace App\Listeners\Admin;

use App\Notifications\AdminCreated;
use App\Events\Admin\AdminWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendAdminAlert
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  AdminWasCreated  $event
     * @return void
     */
    public function handle(AdminWasCreated $event)
    {
        $event->user->notify(new AdminCreated($event->user));
    }
}
