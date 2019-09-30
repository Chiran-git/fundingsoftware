<?php

namespace App\Http\Controllers\Api\Organization;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationResource;
use App\Jobs\Organization\Create as CreateOrganizationJob;
use App\Http\Requests\Organization\CreateOrganizationRequest;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Jobs\Organization\Deactivate as DeactivateOrganizationJob;

class OrganizationsController extends Controller
{
    /**
     * @var OrganizationRepositoryInterface
     */
    private $repo;

    /**
     * @param UserRepositoryInterface $userRepo
     */
    public function __construct(OrganizationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Method to create a new organization
     *
     * This API endpoint also creates the owner user. The user information comes
     * in the same request as the organization
     *
     * @param CreateOrganizationRequest $request Form Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateOrganizationRequest $request)
    {
        $this->authorize('create', Organization::class);

        $organization = dispatch_now(new CreateOrganizationJob($request->all()));

        return response()->json(new OrganizationResource($organization));
    }

    /**
     * Method to get the organization data
     *
     * @param \App\Organization $organization
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Organization $organization, Request $request)
    {
        $this->authorize('view', $organization);

        return response()->json(new OrganizationResource($organization));
    }


    /**
     * Method to mark setup completed for the given organization
     *
     * @param \App\Organization $organization
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function setupComplete(Organization $organization, Request $request)
    {
        if ($this->repo->update($organization->id, ['setup_completed' => true]) !== false) {
            return response()->json([]);
        }

        return response()->json(['message' => __('Unabled to save data.')], 400);
    }

    /**
     * Deactivate the resource from storage.
     *
     * @param  Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivate (Organization $organization) 
    {
        $this->authorize('update', $organization);
        
        $deactivate = dispatch_now(new DeactivateOrganizationJob($organization));
        if ($deactivate) {
            return response()->json([]);
        }

        return response()->json(['message' => __('Unabled to deactivate organization.')], 400);
    }

    /**
     * Soft delete the specified resource from storage.
     *
     * @param  Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy (Organization $organization) 
    {
        $this->authorize('update', $organization);

        if ($organization->delete()) {
            return response()->json([]);
        }
        return response()->json(['message' => __('Unable to delete custom field')], 400);
    }
}
