<?php

namespace App\Listeners\Admin;

use App\Events\Admin\OrganizationWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\OrganizationOwnerCreated;

class SendOwnerAlert
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
     * @param  OrganizationWasCreated  $event
     * @return void
     */
    public function handle(OrganizationWasCreated $event)
    {
        $event->user->notify(new OrganizationOwnerCreated($event->user, $event->organization));
    }
}
