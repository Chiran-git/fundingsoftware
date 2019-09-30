<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $address = $this->address1;
        $id = '';

        if (! empty($this->address2)) {
            $address .= ' ' . $this->address2;
        }

        $address .= ', ' . $this->city . ' ' . $this->state . ' ' . $this->zipcode;

        $manager = app('impersonate');

        if ($request->user()) {
            if ($manager->isImpersonating() || $request->user()->isSuperAdmin() ) {
                $id = $this->id;
            }
        }

        return [
            'id' => $id,
            'slug' => $this->slug,
            'name' => $this->name,
            'address' => $address,
        ];
    }
}
