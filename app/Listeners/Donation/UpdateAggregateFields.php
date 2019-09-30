<?php

namespace App\Listeners\Donation;

use App\Donation;
use App\Events\Donation\DonationWasMade;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateAggregateFields
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
        Donation::updateDonorAggregateFields($event->donation);
        Donation::updateCampaignAggregateFields($event->donation);
    }
}
