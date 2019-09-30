<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationConnectedAccountResource extends JsonResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = OrganizationConnectedAccount::class;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'account_id' => $this->id,
            'account_nickname' => $this->nickname,
            'external_account_object' => $this->external_account_object,
            'external_account_name' => $this->external_account_name,
            'external_account_last4' => $this->external_account_last4,
            'is_default' => $this->is_default,
            'campaigns' => CampaignDetailResource::collection($this->campaigns)
        ];
    }
}
