<?php

namespace App\Http\Resources;

use App\Support\RJ;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignRewardResource extends JsonResource
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
                'title' => $this->title,
                'organization' => $this->organization->name,
                'campaign' => $this->campaign->name,
                'description' => $this->description,
                'min_amount'=> $this->min_amount,
                'quantity'=> $this->quantity,
                'quantity_rewarded' => $this->quantity_rewarded,
                'image' => $this->image ? RJ::assetCdn($this->image) : null,
                'image_filename' => $this->image_filename,
                'image_filesize' => $this->image_filesize
            ];
    }
}
