<?php

namespace App\Http\Controllers\Api\Admin\Campaign;

use App\Campaign;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class CampaignController extends Controller
{
	/**
     * @var CampaignRepositoryInterface
     */
    private $repo;

    /**
     * @param CampaignRepositoryInterface $repo
     */
    public function __construct (CampaignRepositoryInterface $repo)
    {
    	$this->repo = $repo;
    }

    /**
     * Method to list campaigns
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
    	$this->authorize('viewAny', $request->user());

        $status = $request->status;

        if ($status == 'active') {
            $activeCampaigns = Campaign::where('published_at', '<=', now())
                            ->whereNull('disabled_at')
                            ->where(
                                function ($query) {
                                    $query->whereNull('end_date')
                                        ->orWhere('end_date', '>=', now());
                                }
                            )->latest('created_at')->paginate($request->limit);

            return CampaignResource::collection($activeCampaigns);

        } else if ($status == 'completed') {
            $completedCampaigns = Campaign::where([
                ['published_at', '<=', now()],
                ['end_date', '<=', now()]
            ])
            ->whereNull('disabled_at')->latest('end_date')->paginate($request->limit);

            return CampaignResource::collection($completedCampaigns);

        } else {
            return CampaignResource::collection(
                Campaign::paginate($request->limit)
            );
        }
    }

    /**
     * Method to list active campaigns of the organization
     *
     * @param Organization $organization
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function payoutCampaigns(Organization $organization, Request $request)
    {

        return CampaignResource::collection(
                Campaign::where('organization_id', $organization->id)
                    ->where(
                        function ($query) {
                            $query->whereNull('payout_method')
                                ->orWhere('payout_method', '!=', 'bank');
                        }
                    )->get());
    }
}
