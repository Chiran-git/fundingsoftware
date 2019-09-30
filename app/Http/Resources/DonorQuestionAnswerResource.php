<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DonorQuestionAnswerResource extends JsonResource
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
            'organization_id' => $this->organization_id,
            'campaign_id' => $this->campaign_id,
            'donation_id' => $this->donation_id,
            'donor_question_id' => $this->donor_question_id,
            'answer' => $this->answer,
        ];
    }
}
