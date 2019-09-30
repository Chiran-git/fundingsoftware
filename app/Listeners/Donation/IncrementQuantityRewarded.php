<?php

namespace App\Listeners\Donation;

use App\CampaignReward;
use App\Events\Donation\RewardWasGiven;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class IncrementQuantityRewarded
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
     * @param  RewardWasGiven  $event
     * @return void
     */
    public function handle(RewardWasGiven $event)
    {
        CampaignReward::updateQuantityRewarded($event->campaignReward);
    }
}
