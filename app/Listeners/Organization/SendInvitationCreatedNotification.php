<?php

namespace App\Listeners\Organization;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Organization\InvitationWasCreated;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Notifications\JoinCampaign as JoinCampaignNotification;
use App\Notifications\InvitationCreated as InvitationCreatedNotification;

class SendInvitationCreatedNotification
{

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     *
     * @param  InvitationWasCreated  $event
     * @return void
     */
    public function handle(InvitationWasCreated $event)
    {
        //call user repository
        $userRepo = app(UserRepositoryInterface::class);
        //get user
        if ($user = $userRepo->findWhere(['email' => $event->invitation->email])) {

            //check if user is associated with organization
            if ($userOrganization = $user->findAssociatedOrganization($event->invitation->organization_id)) {

                //notify user
                $event->invitation->notify(new JoinCampaignNotification($event->invitation));
            }

        } else {
            //notify user
            $event->invitation->notify(new InvitationCreatedNotification($event->invitation));
        }
    }
}
