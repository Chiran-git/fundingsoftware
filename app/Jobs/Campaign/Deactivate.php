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

class Deactivate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization data
     *
     * @var array
     */
    public $organization;

    /**
     * Campaign data
     *
     * @var array
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
    public function handle(CampaignRepositoryInterface $repo)
    {
        //set end_date to stop the campaign immediately
        return $repo->deactivate($this->campaign->id, 
            [
                //'disabled_at' => date('Y-m-d H:i:s'),
                //'disabled_by_id' => Auth::user()->id,
                'end_date' => date('Y-m-d H:i:s'),
            ]
        );
    }
}
