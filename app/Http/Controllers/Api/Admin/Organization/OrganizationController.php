<?php

namespace App\Http\Controllers\Api\Admin\Organization;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\OrganizationResource;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Jobs\Organization\Create as CreateOrganizationJob;
use App\Http\Requests\Organization\CreateAdminOrganizationRequest;

class OrganizationController extends Controller
{
	/**
     * @var OrganizationRepositoryInterface
     */
    private $repo;

    /**
     * @param OrganizationRepositoryInterface $repo
     */
    public function __construct (OrganizationRepositoryInterface $repo)
    {
    	$this->repo = $repo;
    }

    /**
     * Method to list organizations
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $limit = $request->limit;
        if ($limit == 4) {
            $activeOrganization = Organization::whereNull('deactivated_at')
                                    ->latest('created_at')
                                    ->paginate($request->limit);

            return OrganizationResource::collection($activeOrganization);
        } else {
            return $this->repo->getOrganizationsList(config('pagination.limit'));
        }
    }

    /**
     * Method to create a new organization
     *
     * This API endpoint also creates the owner user. The user information comes
     * in the same request as the organization
     *
     * @param CreateAdminOrganizationRequest $request Form Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(CreateAdminOrganizationRequest $request)
    {
        $organization = dispatch_now(new CreateOrganizationJob($request->all()));

        return response()->json(new OrganizationResource($organization));
    }
}
