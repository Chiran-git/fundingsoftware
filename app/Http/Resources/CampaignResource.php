<?php

namespace App\Http\Resources;

use App\Support\RJ;
use Illuminate\Http\Resources\Json\JsonResource;

class CampaignResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'fundraising_goal' => $this->fundraising_goal,
            'funds_raised' => $this->funds_raised,
            'total_donations' => $this->total_donations,
            'end_date' => $this->end_date,
            'image' => $this->image ? RJ::assetCdn($this->image) : null,
            'payout_connected_account_id' => $this->payout_connected_account_id,
            'payout_name' => $this->payout_name,
            'payout_organization_name' => $this->payout_organization_name,
            'payout_address1' => $this->payout_address1,
            'payout_address2' => $this->payout_address2,
            'payout_city' => $this->payout_city,
            'payout_state' => $this->payout_state,
            'payout_zipcode' => $this->payout_zipcode,
            'payout_country_id' => $this->payout_country_id,
            'payout_payable_to' => $this->payout_payable_to,
            'payout_schedule' => $this->payout_schedule,
            'description' => $this->description,
            'published_at' => $this->published_at,
            'created_at' => $this->created_at,
        ];
    }
}
