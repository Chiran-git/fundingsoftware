<?php

namespace App\Listeners\Admin;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Organization\OwnerWasCreated;
use App\Notifications\OrganizationOwnerCreated;

class SendPasswordResetNotification
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
     * @param  OwnerWasCreated  $event
     * @return void
     */
    public function handle(OwnerWasCreated $event)
    {
        $event->user->notify(new OrganizationOwnerCreated($event->user, $event->organization));
    }
}
