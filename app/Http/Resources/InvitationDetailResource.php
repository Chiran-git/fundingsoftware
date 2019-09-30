<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class InvitationDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        //get campaigns collection for invitations
        $campaignRepo = app(CampaignRepositoryInterface::class);
        $campaigns = $campaignRepo->getWhereIdIn($this->campaign_ids);

        return [
            'id' => $this->id,
            'organization' => new OrganizationResource($this->organization),
            'campaigns' => CampaignResource::collection($campaigns),
            'invitee' => new UserResource($this->invitee),
            'user' => new UserResource($this->user),
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'role' => $this->role,
            'code' => $this->code,
            'accepted_at' => $this->accepted_at,
            'expires_at' => $this->expires_at,

        ];
    }
}
