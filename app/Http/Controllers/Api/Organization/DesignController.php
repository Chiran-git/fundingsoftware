<?php

namespace App\Http\Controllers\Api\Organization;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Http\Requests\Organization\UpdateDesignRequest;
use App\Jobs\Organization\UpdateDesign as UpdateOrganizationDesignJob;

class DesignController extends Controller
{
    /**
     * Method to update the design fields of the organization
     *
     * @param \App\Organization $organization
     * @param \App\Http\Requests\Organization\UpdateDesignRequest $request
     * @return void
     */
    public function update(Organization $organization, UpdateDesignRequest $request)
    {
        $attributes = $request->only(
            [
                'cover_image',
                'logo',
                'primary_color',
                'secondary_color',
                'appeal_headline',
                'appeal_message',
                'appeal_photo',
            ]
        );

        if (dispatch_now(new UpdateOrganizationDesignJob($organization, $attributes)) !== false) {
            return response()->json(new OrganizationResource($organization->refresh()));
        }

        return response()->json(['message' => __('Unable to save the organization.')], 400);
    }
}
