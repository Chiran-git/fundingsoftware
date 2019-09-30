<?php

namespace App\Jobs\Campaign;

use App\Organization;
use App\CampaignReward;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\CampaignRewardRepositoryInterface;

class UpdateReward implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $imageFields = [
        'image'
    ];

    /**
     * Organization
     *
     * @var App\Organization;
     */
    private $organization;

    /**
     * Reward
     *
     * @var App\CampaignReward
     */
    private $reward;

    /**
     * Reward data
     *
     * @var array
     */
    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, CampaignReward $reward, $data)
    {
        $this->organization = $organization;
        $this->reward = $reward;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(CampaignRewardRepositoryInterface $repo)
    {
        $this->updateImages($repo);

        // Update the other attributes
        return $repo->updateAll(
            $this->reward->id,
            [
                'title' => $this->data['title'],
                'description' => $this->data['description'],
                'min_amount' => $this->data['min_amount'],
                'quantity' => $this->data['quantity'],
            ]
        );
    }

     /**
     * Update the image fields for the reward
     *
     * @return boolean
     */
    private function updateImages($repo)
    {
        foreach ($this->imageFields as $field) {
            // If we have the field in the data
            if(array_key_exists($field, $this->data)){
                if (! empty($this->data[$field])) {
                    $repo->updateImage(
                        $this->reward->id,
                        $this->data[$field],
                        $field
                    );
                } else {
                    $repo->deleteImage($this->reward->id);
                }
            }
        }
    }
}
