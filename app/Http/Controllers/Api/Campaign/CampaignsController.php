<?php

namespace App\Http\Controllers\Api\Campaign;

use DB;
use App\Donation;
use App\Campaign;
use Carbon\Carbon;
use App\Support\RJ;
use App\Organization;
use App\CampaignReward;
use App\DonationReward;
use App\CampaignCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\CampaignDetailResource;
use App\Jobs\Campaign\Create as CreateCampaignJob;
use App\Jobs\Campaign\Update as UpdateCampaignJob;
use App\Http\Requests\Campaign\SaveCampaignRequest;
use App\Jobs\Campaign\Publish as PublishCampaignJob;
use App\Http\Requests\Campaign\UpdateCampaignRequest;
use App\Jobs\Campaign\Deactivate as DeactivateCampaignJob;
use App\Jobs\Campaign\Reactivate as ReactivateCampaignJob;
use App\Repositories\Contracts\CampaignRepositoryInterface;

class CampaignsController extends Controller
{
    /**
     * @var CampaignRepositoryInterface
     */
    private $repo;

    /**
     * @param CampaignRepositoryInterface $campaignRepo
     */
    public function __construct(CampaignRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Method to create campaign for organization
     *
     * @param Organization $organization
     * @param SaveCampaignRequest $request
     * @return void
     */
    public function store(Organization $organization, SaveCampaignRequest $request)
    {
        $user = auth()->user();
        if ($campaign = dispatch_now(new CreateCampaignJob($organization, $user, $request->all()))) {

            return response()->json(new CampaignDetailResource($campaign->refresh()));
        }

        return response()->json(['message' => __('Unable to save the campaign.')], 400);
    }

    /**
     * Method to get the campaign data
     *
     * @param \App\Organization $organization
     * @param \App\Campaign     $campaign
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Organization $organization, Campaign $campaign)
    {
        if ($campaign->organization_id != $organization->id) {
            return response()->json(['message' => __('Not authorized.')], 403);
        }
        $this->authorize('view', $campaign);

        return response()->json(new CampaignDetailResource($campaign));
    }

    /**
     * Method to update campaign for organization
     *
     * @param Organization $organization
     * @param SaveCampaignRequest $request
     * @return void
     */
    public function update(
        Organization $organization,
        Campaign $campaign,
        UpdateCampaignRequest $request
    ) {
        if ($campaign = dispatch_now(new UpdateCampaignJob($organization, $campaign, $request->all()))) {

            return response()->json(new CampaignDetailResource($campaign->refresh()));
        }

        return response()->json(['message' => __('Unable to save the campaign.')], 400);
    }

    /**
     * Method to publish campaign for organization
     *
     * @param Organization $organization
     * @param Campaign $campaign
     *
     * @return void
     */
    public function publish(Organization $organization, Campaign $campaign)
    {
        $this->authorize('view', $campaign);

        if ($campaign = dispatch_now(new PublishCampaignJob($organization, $campaign))) {
            return response()->json([]);
        }
        return response()->json(['message' => __('Unable to publish campaign.')], 400);
    }

    /**
     * Display listing of campaigns
     *
     * @param \App\Organization $organization
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request, Organization $organization)
    {
        $this->authorize('view', $organization);

        $userOrganization = $request->user()->findAssociatedOrganization($organization->id);

        if ($request->status == 'active') {
            $campaigns = Campaign::where('published_at', '<=', now())
                            ->whereNull('disabled_at')
                            ->where(
                                function ($query) {
                                    $query->whereNull('end_date')
                                        ->orWhere('end_date', '>=', now());
                                }
                            )
                            ->where('organization_id', '=', $organization->id)
                            ->latest('created_at')->paginate(config('pagination.limit'));
        } else {
            $campaigns = $this->repo->findAllQueryWhere([
                            'organization_id' => $organization->id
                        ])
                        ->orderBy('created_at', 'desc')
                        ->paginate(config('pagination.limit'));
        }

        if ($userOrganization->pivot->role == 'campaign-admin') {
            return CampaignResource::collection(
                $this->repo->findAllCampaignsForCampaignAdmin($organization->id, $request->user()->id, $request->status, config('pagination.limit'))
            );
        } else {
            return CampaignResource::collection($campaigns);
        }
    }

    /**
     * Get the listing of campaigns to generate dropdown
     *
     * @param \App\Organization $organization
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Organization $organization, Request $request)
    {
        $this->authorize('view', $organization);
        $userId = null;

        if ($request->user()->currentRole() == 'campaign-admin') {
            $userId = $request->user()->id;
        }
        $campaigns = $this->repo->getCampaignListing($organization->id, $userId);

        return response()->json($campaigns);
    }

    /**
     * Get the campaign statistics
     *
     * @param \App\Organization $organization
     * @param \App\Campaign $campaign
     * @param Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function campaignStats (Organization $organization, Campaign $campaign, Request $request)
    {
        $offset = $request->offset;

        if (! $offset) {
            $offset = Carbon::now('America/New_York')->utcOffset();
        }

        $totalDonation = Donation::where
                            ([
                                'organization_id'=>$organization->id,
                                'campaign_id' => $campaign->id
                            ])
                            ->sum('gross_amount');

        $netDonation = Donation::where
                            ([
                                'organization_id'=>$organization->id,
                                'campaign_id' => $campaign->id
                            ])
                            ->sum('net_amount');

        $noOfDonation   = Donation::where
                            ([
                                'organization_id'=>$organization->id,
                                'campaign_id' => $campaign->id
                            ])
                            ->count();

        if ($noOfDonation) {
            $averageDonation = $totalDonation/$noOfDonation;
        } else {
            $averageDonation = $totalDonation;
        }

        $totalDonors     = Donation::where
                            ([
                                'organization_id'=>$organization->id,
                                'campaign_id' => $campaign->id
                            ])
                            ->distinct('donor_id')->count('donor_id');


        $rewardsEarned = DonationReward::where
                            ([
                                'organization_id'=>$organization->id,
                                'campaign_id' => $campaign->id
                            ])
                            ->count();

        // Getting data for chart
        $bpi = array();

        $donations = Donation::where([
                                'organization_id'=>$organization->id,
                                'campaign_id' => $campaign->id
                            ])
                    ->select(DB::raw('SUM(gross_amount) as total, DATE(DATE_ADD(created_at, INTERVAL '.$offset.' MINUTE)) as donation_date'))
                    ->orderBy('donation_date')
                    ->groupBy('donation_date')
                    ->get();

        foreach ($donations as $donation) {
            $bpi[$donation->donation_date] = round($donation->total / 100, 2);
        }

        $firstDonation = $campaign->donations()
            ->select(DB::raw('DATE(DATE_ADD(created_at, INTERVAL '.$offset.' MINUTE)) as donation_date'))
            ->orderBy('created_at', 'asc')
            ->first();

        $startDate = today()->addDay();
        if ($firstDonation) {
            $startDate = Carbon::parse($firstDonation->donation_date);
        }

        // Adding a day to today so that we get donations till midnight tonight
        $today = today()->addDay();

        while ($startDate->notEqualTo($today)) {
            $date = $startDate->format('Y-m-d');
            if (! isset($bpi[$date])) {
                $bpi[$date] = 0;
            }
            $startDate->addDay();
        }

        ksort($bpi);

        $data = array(
                    'total_donation' => RJ::donationMoney($totalDonation/100),
                    'net_donation' => RJ::donationMoney($netDonation/100),
                    'total_donors' => $totalDonors,
                    'average_donation' => RJ::donationMoney($averageDonation/100),
                    'rewards_earned' => $rewardsEarned,
                    "bpi"=>$bpi
                );
        return response()->json($data, 200);
    }

    /**
     * Deactivate the resource from storage.
     *
     * @param  Organization $organization
     * @param  Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deactivate(organization $organization, Campaign $campaign)
    {
        $this->authorize('view', $campaign);

        $deactivate = dispatch_now(new DeactivateCampaignJob($organization, $campaign));

        if ($deactivate) {
            return response()->json([]);
        }

        return response()->json(['message' => __('Unabled to deactivate campaign.')], 400);
    }

    /**
     * Deactivate the resource from storage.
     *
     * @param  Organization $organization
     * @param  Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function reactivate(organization $organization, Campaign $campaign)
    {
        $this->authorize('view', $campaign);

        $reactivate = dispatch_now(new ReactivateCampaignJob($organization, $campaign));

        if ($reactivate) {
            return response()->json([]);
        }

        return response()->json(['message' => __('Unabled to reactivate campaign.')], 400);
    }

    /**
     * Method to get all the categories
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function categories()
    {
        $categories = CampaignCategory::all();
        return response()->json($categories);
    }
}
