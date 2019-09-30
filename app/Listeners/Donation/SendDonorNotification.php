<?php

namespace App\Listeners\Donation;

use App\Mail\Donation;
use App\Notifications\DonationRecorded;
use App\Events\Donation\DonationWasMade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendDonorNotification
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
     * @param  DonationWasMade  $event
     * @return void
     */
    public function handle(DonationWasMade $event)
    {
        $event->donation->donor->notify(new DonationRecorded($event->donation));
    }
}
