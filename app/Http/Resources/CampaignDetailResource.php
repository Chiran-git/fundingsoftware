<?php

namespace App\Http\Resources;

use App\Support\RJ;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'status' => $this->present()->status(),
            'organization' => new OrganizationResource($this->organization),
            'createdBy' => new UserResource($this->createdBy),
            'name' => $this->name,
            'slug' => $this->slug,
            'fundraising_goal' => $this->fundraising_goal,
            'funds_raised' => $this->funds_raised,
            'total_donations' => $this->total_donations,
            'end_date' => $this->end_date,
            'image' => $this->image ? RJ::assetCdn($this->image) : null,
            'image_filename' => $this->image_filename,
            'video_url' => $this->video_url,
            'description' => $this->description,
            'donor_message' => $this->donor_message,
            'payout_method' => $this->payout_method,
            //'payout_connected_account' => new CampaignPayoutResource($this->connected_account),
            'payout_connected_account' => $this->connected_account,
            'payout_name' => $this->payout_name,
            'payout_address1' => $this->payout_address1,
            'payout_address2' => $this->payout_address2,
            'payout_city' => $this->payout_city,
            'payout_state' => $this->payout_state,
            'payout_zipcode' => $this->payout_zipcode,
            'payout_country' => new CountryResource($this->payout_country),
            'payout_payable_to' => $this->payout_payable_to,
            'payout_schedule' => $this->payout_schedule,
            'sort_order' => $this->sort_order,
            'published_at' => $this->published_at,
            'publishedBy' => new UserResource($this->publishedBy),
        ];
    }
}
