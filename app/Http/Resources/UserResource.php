<?php

namespace App\Http\Resources;

use App\Support\RJ;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'user_type' => $this->user_type,
            'last_login_at' => $this->last_login_at,
            'job_title' => $this->job_title,
            'image' => $this->image ? RJ::assetCdn($this->image) : null,
        ];
    }
}
