<?php

namespace App\Http\Controllers\Api\Donation;

use DB;
use Auth;
use App\Donor;
use App\Campaign;
use App\Donation;
use Carbon\Carbon;
use App\Support\RJ;
use App\CampaignUser;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DonationResource;
use App\Repositories\Contracts\DonationRepositoryInterface;

class DonationsController extends Controller
{

    /**
     * @var DonationRepositoryInterface
     */
    private $repo;

    public function __construct (DonationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Method to get a list of all donor questions for the given organization
     *
     * @param Organization $organization
     * @param DonationRepositoryInterface $repo
     * @param Request $request
     *
     * @return AnonymousResourceCollection
     */
    public function index(
        Organization $organization,
        Donor $donor,
        Request $request
    ) {
        $this->authorize('view', $organization);

        if ($request->user()->currentRole() == 'campaign-admin') {
            $campaignIds = CampaignUser::where('organization_id', $organization->id)
                ->where('user_id', $request->user()->id)
                ->pluck('campaign_id');
            $donations = $this->repo->findAllWhere([
                'organization_id' => $organization->id,
                'donor_id' => $donor->id,
                //'campaign_id' => $campaignIds,
            ])
            ->whereIn('campaign_id',$campaignIds)
            ->sortByDesc('created_at');

        } else {
            $donations = $this->repo->findAllWhere([
                'organization_id' => $organization->id,
                'donor_id' => $donor->id
            ])->sortByDesc('created_at');
        }

        return DonationResource::collection(
            $donations
        );
    }

    /**
     * Method to get donation statistics of an organization
     *
     * @param Organization $organization
     * @param Request      $request
     *
     * @return array
     */
    public function donationStats(Organization $organization, Request $request)
    {
        $userCampaignIds = [];

        if ($request->user()->currentRole() == 'campaign-admin') {
            $userCampaignIds = $request->user()
                ->getAssignedCampaignsForOrganization($organization)
                ->pluck('id')
                ->toArray();
        }

        $fundsRaisedQuery = Campaign::where('organization_id', $organization->id);

        $averageDonationQuery = Donation::where('organization_id', $organization->id);

        $totalDonorsQuery = Donation::where('organization_id', $organization->id)
            ->distinct('donor_id');

        $activeCampaignsQuery = Campaign::where('campaigns.organization_id', $organization->id)
            ->active();

        // If we have campaignIds to limit with, then add it to various queries.
        if (! empty($userCampaignIds)) {
            $fundsRaisedQuery->whereIn('id', $userCampaignIds);
            $averageDonationQuery->whereIn('campaign_id', $userCampaignIds);
            $totalDonorsQuery->whereIn('campaign_id', $userCampaignIds);
            $activeCampaignsQuery->whereIn('id', $userCampaignIds);
        }

        $fundsRaised = $fundsRaisedQuery->sum('funds_raised');
        $totalDonors = $totalDonorsQuery->count('donor_id');
        $averageDonation = $averageDonationQuery->average('gross_amount');
        $activeCampaigns = $activeCampaignsQuery->count();

        $data = [
            'all_time_donations'    => RJ::donationMoney($fundsRaised / 100),
            'average_donation'      => RJ::donationMoney($averageDonation / 100),
            'total_donors'          => $totalDonors,
            'active_campaigns'      => $activeCampaigns
        ];

        return response()->json($data, 200);
    }

    /**
     * Method to get data for donation chart
     *
     * @param Organization $organization
     * @param Request      $request
     *
     * @return array
     */
    public function chartData(organization $organization, Request $request)
    {
        $offset = $request->offset;

        if (!$offset) {
            $offset = Carbon::now('America/New_York')->utcOffset();
        }

        // To be refactored later
        $bpi = array();

        $userCampaignIds = [];

        if ($request->user()->currentRole() == 'campaign-admin') {
            $userCampaignIds = $request->user()
                ->getAssignedCampaignsForOrganization($organization)
                ->pluck('id')
                ->toArray();
        }

        // Find the sum of donations in last 15 days.
        $donationsQuery = Donation::where('organization_id', $organization->id);

        // If we have to filter for campaign ids
        if (! empty($userCampaignIds)) {
            $donationsQuery->whereIn('campaign_id', $userCampaignIds);
        }

        $donations = $donationsQuery->select(DB::raw('SUM(gross_amount) as total, date(DATE_ADD(created_at, INTERVAL '.$offset.' MINUTE)) as donation_date'))
            ->where('created_at', '>=', today()->subDays(15))
            ->orderBy('donation_date')
            ->groupBy('donation_date')
            ->get();

        foreach ($donations as $donation) {
            $bpi[$donation->donation_date] = round($donation->total / 100, 2);
        }

        // If we have at least 1 donation in past 15 days, then we will build the array for
        // all missing days. If not donation, then just return an empty data set
        if (empty($bpi)) {
            return response()->json(['bpi' => $bpi]);
        }

        $startDate = today()->subDays(15);
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

        return response()->json(['bpi' => $bpi]);
    }

    /**
     * Method to get a list of all donations questions for the given organization
     *
     * @param Organization $organization
     * @param Request $request
     *
     * @return AnonymousResourceCollection
     */
    public function list(
        Organization $organization
    ) {
        $this->authorize('view', $organization);
        return response()->json($this->repo->getDonationsList($organization->id, config('pagination.limit')));
    }

    /**
     * List the top 5 recent donations of particular campaign
     *
     * @param \App\Organization $organization
     * @param \App\Campaign $campaign
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function recentCampaignDonations (Organization $organization, Campaign $campaign, $limit = null)
    {
        $this->authorize('view', $organization);

        if ($limit) {
            return DonationResource::collection(
                $this->repo->findAllWhere([
                    'organization_id' => $organization->id,
                    'campaign_id' => $campaign->id
                ])->sortByDesc('created_at')->take($limit)
            );
        } else {
            return DonationResource::collection(
                $this->repo->findAllWhere([
                    'organization_id' => $organization->id,
                    'campaign_id' => $campaign->id
                ])->sortByDesc('created_at')
            );
        }
    }
}
