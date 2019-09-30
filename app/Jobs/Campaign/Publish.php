<?php

namespace App\Jobs\Campaign;

use Auth;
use App\Campaign;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class Publish implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * Campaign
     *
     * @var \App\Campaign
     */
    public $campaign;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, Campaign $campaign)
    {
        $this->organization = $organization;
        $this->campaign = $campaign;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CampaignRepositoryInterface $campaignRepo)
    {
        $publish = $campaignRepo->update(
            $this->campaign->id,
            [
                'published_at' => date('Y-m-d H:i:s'),
                'published_by_id' => Auth::user()->id
            ]
        );

        return $publish;
    }
}
