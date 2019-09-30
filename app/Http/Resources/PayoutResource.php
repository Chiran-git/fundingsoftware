<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PayoutResource extends JsonResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = Payout::class;

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
            'organization_id' => $this->organization_id,
            'campaign_id' => $this->campaign_id,
            'organization_connected_account' => new OrganizationConnectedAccountResource($this->account),
            'payout_name' => $this->payout_name,
            'payout_address1' => $this->payout_address1,
            'payout_address2' => $this->payout_address2,
            'payout_city' => $this->payout_city,
            'payout_state' => $this->payout_state,
            'payout_zipcode' => $this->payout_zipcode,
            'payout_country_id' => $this->payout_country_id,
            'payout_payable_to' => $this->payout_payable_to,
            'issue_date' => $this->issue_date,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'deposit_amount' => $this->deposit_amount,
            'gross_amount' => $this->gross_amount,
            'deleted_at' => $this->deleted_at,
            'created_at' => $this->created_at,
            'payout_address2' => $this->payout_address2,
            'updated_at' => $this->updated_at,
        ];
    }
}
