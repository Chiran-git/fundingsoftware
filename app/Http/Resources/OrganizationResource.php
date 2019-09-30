<?php

namespace App\Http\Resources;

use App\Support\RJ;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\CurrencyRepositoryInterface;

class OrganizationResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'address1' => $this->address1,
            'address2' => $this->address2,
            'city' => $this->city,
            'state' => $this->state,
            'zipcode' => $this->zipcode,
            'phone' => $this->phone,
            'country' => new CountryResource($this->country),
            'currency' => new CurrencyResource($this->currency),
            'owner' => new UserResource($this->owner),
            'cover_image' => $this->cover_image ? RJ::assetCdn($this->cover_image) : null,
            'logo' => $this->logo ? $this->logo : null,
            'appeal_photo' => $this->appeal_photo ? RJ::assetCdn($this->appeal_photo) : null,
            'primary_color' => Str::startsWith($this->primary_color, '#') ? $this->primary_color : '#'.$this->primary_color,
            'secondary_color' => Str::startsWith($this->secondary_color, '#') ? $this->secondary_color : '#'.$this->secondary_color,
            'appeal_headline' => $this->appeal_headline,
            'appeal_message' => $this->appeal_message,
            'system_donor_questions' => json_decode($this->system_donor_questions),
            'deactivated_at' => $this->deactivated_at,
            'created_at' => $this->created_at,
            'no_of_campaigns' => $this->noOfCampaigns(),
            'total_donations' => $this->totalDonationReceived(),
            'net_donations' => $this->netdonationReceived(),
        ];
    }
}
