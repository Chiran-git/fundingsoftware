<?php

namespace App\Jobs\Campaign;

use App\Campaign;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class UpdatePayout implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Campaign
     *
     * @var \App\Campaign
     */
    public $campaign;

    /**
     * Campaign data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Campaign $campaign, $data)
    {
        $this->campaign = $campaign;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param \App\Repositories\Contracts\CampaignRepositoryInterface $campaignRepo
     *
     * @return void
     */
    public function handle(CampaignRepositoryInterface $campaignRepo)
    {
        // Update the other attributes
        return $campaignRepo->update(
            $this->campaign->id,
            [
                'payout_method' => $this->data['payout_method'],
                'payout_name'  => $this->data['payout_name'],
                'payout_organization_name' => $this->data['payout_organization_name'],
                'payout_address1' => $this->data['payout_address1'],
                'payout_address2' => $this->data['payout_address2'],
                'payout_city'  => $this->data['payout_city'],
                'payout_state'  => $this->data['payout_state'],
                'payout_zipcode'  => $this->data['payout_zipcode'],
                'payout_country_id'  => $this->data['payout_country_id'],
                'payout_payable_to'  => $this->data['payout_payable_to'],
                'payout_schedule'  => $this->data['payout_schedule'],
            ]
        );
    }
}
