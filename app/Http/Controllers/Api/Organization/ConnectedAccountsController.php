<?php
namespace App\Http\Controllers\Api\Organization;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\OrganizationConnectedAccount;
use App\Http\Resources\OrganizationConnectedAccountResource;
use App\Http\Requests\Organization\UpdateConnectedAccountRequest;
use App\Jobs\Organization\UpdateConnectedAccount as UpdateConnectedAccountJob;
use App\Jobs\Organization\DeleteConnectedAccount as DeleteConnectedAccountJob;
use App\Repositories\Contracts\OrganizationConnectedAccountRepositoryInterface;

class ConnectedAccountsController extends Controller
{
    /**
     * @var OrganizationConnectedAccountRepositoryInterface
     */
    private $repo;

    /**
     * @param OrganizationConnectedAccountRepositoryInterface $organizationRepo
     */
    public function __construct(OrganizationConnectedAccountRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }


	/**
     * Method to update account of organization
     *
     * @param Organization                  $organization
     * @param OrganizationConnectedAccount  $organizationConnectedAccount
     * @param UpdateConnectedAccountRequest $request
     *
     * @return void
     */
	public function update(
        Organization $organization,
        OrganizationConnectedAccount $organizationConnectedAccount,
        UpdateConnectedAccountRequest $request
    )
    {
        $this->authorize('update', [$organizationConnectedAccount, $organization]);

        $updateAccount = dispatch_now(new UpdateConnectedAccountJob($organization, $organizationConnectedAccount, $request->all()));

        if ($updateAccount !== false) {
            return response()->json(
                new OrganizationConnectedAccountResource($organizationConnectedAccount->refresh())
            );
        }
    }

    /**
     * Display a listing of the connected accounts.
     *
     * @param \App\Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Organization $organization)
    {
        $this->authorize('view', $organization);

        return OrganizationConnectedAccountResource::collection(
            $this->repo->findAllWhere([
                'organization_id' => $organization->id
            ])
        );
    }

    /**
     * Method to delete account of organization
     *
     * @param Organization                  $organization
     * @param OrganizationConnectedAccount  $organizationConnectedAccount
     *
     * @return void
     */
	public function delete(
        Organization $organization,
        OrganizationConnectedAccount $organizationConnectedAccount
    )
    {
        $this->authorize('delete', [$organizationConnectedAccount, $organization]);
        
        //delete connected account
        $deleteAccount = dispatch_now(new deleteConnectedAccountJob($organization, $organizationConnectedAccount));

        if ($deleteAccount !== false) {
            return response()->json(
                new OrganizationConnectedAccountResource($organizationConnectedAccount->refresh())
            );
        }
    }

}
