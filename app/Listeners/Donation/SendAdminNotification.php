<?php

namespace App\Listeners\Donation;

use App\Mail\Donation;
use App\Events\Donation\DonationWasMade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\DonationRecordedAdmin;
use Illuminate\Support\Facades\Notification;

class SendAdminNotification
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
     * @param  DonationWasMade  $event
     * @return void
     */
    public function handle(DonationWasMade $event)
    {
        //get organization admins
        if ($orgAdmins = $event->donation->organization->users()
            ->wherePivotIn('role', ['owner', 'admin'])
            ->get()) {

            Notification::send($orgAdmins, new DonationRecordedAdmin($event->donation));
        }
        
        // Get the campaign users
        if ($campaignUsers = $event->donation->campaign->users()->get()) {

            Notification::send($campaignUsers, new DonationRecordedAdmin($event->donation));   
        }
    }
}
