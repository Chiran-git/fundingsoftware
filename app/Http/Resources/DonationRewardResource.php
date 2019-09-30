<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationRewardResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return
            [
                'id' => $this->id,
                // 'organization_id' => $this->organization_id,
                // 'campaign_id' => $this->campaign_id,
                // 'donation_id' => $this->donation_id,
                // 'campaign_reward_id'=> $this->campaign_reward_id,
                'reward' => $this->campaign_reward->title,
            ];
    }
}
