<?php

namespace App\Jobs\Campaign;

use App\Campaign;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\CampaignRewardRepositoryInterface;

class CreateReward implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization
     *
     * @var App\Organization;
     */
    private $organization;

    /**
     * Campaign
     *
     * @var App\Campaign
     */
    private $campaign;

    /**
     * Campaign data
     *
     * @var array
     */
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, Campaign $campaign, $data)
    {
        $this->organization = $organization;
        $this->campaign = $campaign;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CampaignRewardRepositoryInterface $campaignRewardRepo)
    {
        $imagePath = "";
        //upload image
        if (!empty($this->data['image'])) {
            $uploadPath = '/organizations/' . $this->organization->id;
            $image = $this->data['image'];
            if(! $imagePath = $campaignRewardRepo->storeImage($uploadPath, $image)) {
                return false;
            }
        }

        if ($campaignReward = $campaignRewardRepo->store(
            [
                'organization_id' => $this->organization->id,
                'campaign_id' => $this->campaign->id,
                'title' => $this->data['title'],
                'description' => $this->data['description'],
                'min_amount' => $this->data['min_amount'],
                'quantity' => $this->data['quantity'],
                'image' => $imagePath ? $imagePath : null,
                'image_filename' => $imagePath ? $image->getClientOriginalName() : null,
                'image_filesize' => $imagePath ? $image->getFileInfo()->getSize() : null
            ]
        )) {
            return $campaignReward;
        }

        if (!empty($imagePath)) {
            $campaignRewardRepo->deleteImageFromStorage('public_uploads', $imagePath);
        }
        return false;
    }
}
