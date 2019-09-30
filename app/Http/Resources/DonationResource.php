<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonationResource extends JsonResource
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
            'organization' => new OrganizationResource($this->organization),
            'campaign_id' => $this->campaign_id,
            'donor' => new DonorResource($this->donor),
            'currency_id' => $this->currency_id,
            'gross_amount' => $this->gross_amount,
            'net_amount' => $this->net_amount,
            'card_name' => $this->card_name,
            'card_brand' => $this->card_brand,
            'card_last_four' => $this->card_last_four,
            'payout_method' => $this->payout_method,
            'entry_type' => $this->entry_type,
            'donation_method' => $this->donation_method,
            'mailing_address1' => $this->mailing_address1,
            'mailing_address2' => $this->mailing_address2,
            'mailing_city' => $this->mailing_city,
            'mailing_state' => $this->mailing_state,
            'mailing_zipcode' => $this->mailing_zipcode,
            'billing_address1' => $this->billing_address1,
            'billing_address2' => $this->billing_address2,
            'billing_city' => $this->billing_city,
            'billing_state' => $this->billing_state,
            'billing_zipcode' => $this->billing_zipcode,
            'created_at' => $this->created_at,
            'campaign' => new CampaignResource($this->campaign),
            'currency' => new CurrencyResource($this->currency),
            'reward' => new DonationRewardResource($this->reward),
        ];
    }
}
