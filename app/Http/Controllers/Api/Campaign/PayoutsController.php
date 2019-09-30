<?php

namespace App\Http\Controllers\Api\Campaign;

use App\Payout;
use App\Campaign;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PayoutResource;
use App\Http\Resources\CampaignPayoutResource;
use App\Jobs\Campaign\UpdatePayout as UpdatePayoutJob;
use App\Repositories\Contracts\CampaignRepositoryInterface;
use App\Repositories\Contracts\PayoutRepositoryInterface;
use App\Repositories\Contracts\OrganizationConnectedAccountRepositoryInterface;

class PayoutsController extends Controller
{
    /**
     * @var CampaignRepositoryInterface
     */
    private $repo;

    /**
     * @param CampaignRepositoryInterface $userRepo
     */
    public function __construct(CampaignRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @param App\Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Organization $organization)
    {
        return new CampaignPayoutResource($organization);
    }

    /**
     * Method to update payout for campaign
     *
     * @param Request       $request
     * @param Organization  $organization
     * @param Campaign      $campaign
     * @return void
     */
    public function update(
        Request $request,
        Organization $organization,
        Campaign $campaign
    )
    {
        //check if user is authorize to update campaign
        $this->authorize('update', [$campaign, $organization]);

        if (dispatch_now(new UpdatePayoutJob($campaign, $request->all())) !== false) {
            return response()->json([]);
        }

        return response()->json(['message' => __('Unable to update campaign payout.')], 400);
    }

    /**
     * Method to update payout for campaign
     *
     * @param Request       $request
     * @param Organization  $organization
     * @return void
     */
    public function list(
        Request $request,
        Organization $organization,
        OrganizationConnectedAccountRepositoryInterface $repo
    )
    {
        //check if user is authorize to update campaign
        $this->authorize('view', $organization);

        $connectedAccounts = $repo->getAccountListing($organization->id);

        return response()->json($connectedAccounts);

    }


    /**
     * Get the listing of particular campaign payouts
     *
     * @param \App\Organization $organization
     * @param \App\Campaign $campaign
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function campaignPayoutList(
        Organization $organization, 
        Campaign $campaign,
        PayoutRepositoryInterface $payoutRepo
    ) {
        $this->authorize('view', $organization);

        return PayoutResource::collection(
            $payoutRepo->findAllWhere([
                'organization_id' => $organization->id,
                'campaign_id' => $campaign->id
            ])
        );
    }
}
