<?php

namespace App\Http\Controllers;

use App\Campaign;
use App\Donation;
use App\Organization;
use App\CampaignReward;
use Illuminate\Http\Request;
use App\Exports\DonationsExport;
use App\Jobs\Donation\MakeDonation;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Resources\DonationResource;
use App\Repositories\Contracts\DonationRepositoryInterface;
use App\Http\Requests\Donation\StoreRequest as DonationStoreRequest;
use App\Http\Requests\Donation\CreateRequest As DonationCreateRequest;

class DonationsController extends Controller
{

    /**
     * Method to create new donation
     *
     * @param App\Organization
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Organization $organization, Request $request)
    {
        $this->authorize('view', $organization);

        return view('donations.donor-manual-payment');
    }

    /**
     * Method to display the donations listing
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('view', $request->user()->currentOrganization());

        return view('donations.index');
    }

    /**
     * Method to export organization donations
     *
     * @param \App\Organization $organization
     * @param App\Repositories\Contracts\DonationRepositoryInterface $repo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Organization $organization, DonationRepositoryInterface $repo)
    {
        $this->authorize('view', $organization);

        $file_name = 'Donations-List-' . $organization->id . '.csv';

        return Excel::download(new DonationsExport($organization, $repo), $file_name);
    }


    /**
     * Method to show the donation form to the donor
     * This method takes the amount and reward as GET params
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Organization $organization
     * @param \App\Campaign $campaign
     *
     * @return \Illuminate\View\View
     */
    public function createForDonor(DonationCreateRequest $request, Organization $organization, Campaign $campaign)
    {
        $reward = null;

        if ($request->reward) {
            $reward = CampaignReward::findOrFail($request->reward);

            if ($reward->quantity_rewarded >= $reward->quantity) {
                return redirect()->route('organization.show', ['orgSlug' => $organization->slug])->with('errorMessage', __('Reward no longer available.'));
            }
        }
        if (isset($organization->system_donor_questions) && ! empty($organization->system_donor_questions)) {
            $organization->system_donor_questions = json_decode($organization->system_donor_questions);
        }
        // Get the organization country
        $country = $organization->country;

        return view('donations.create-for-donor')->with(compact('organization', 'campaign', 'reward', 'country'));
    }

    /**
     * Method to make a new donation by the donor (public)
     *
     * @param DonationStoreRequest $request
     * @param Campaign $campaign
     *
     * @return \Illuminate\Response\JsonResponse
     */
    public function store(DonationStoreRequest $request, Campaign $campaign)
    {
        $reward = null;
        if ($request->reward) {
            $reward = CampaignReward::findOrFail($request->reward);
        }

        $donation = dispatch_now(new MakeDonation($campaign, $reward, $request->all()));

        // If we have an error response, then respond with the error
        if (! empty($donation['errors'])) {
            return response()->json(
                [
                    'message' => __('Unable to process the donation.'),
                    'errors' => $donation['errors'],
                ],
                422
            );
        }

        return new DonationResource($donation);
    }

    /**
     * Method to show the success page after a donation is made
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Organization $organization
     * @param \App\Campaign $campaign
     * @param \App\Donation $donation
     *
     * @return \Illuminate\View\View
     */
    public function success(
        Request $request,
        Organization $organization,
        Campaign $campaign,
        Donation $donation
    ) {
        // If the donation's data doesn't match with org and campaign then abort
        if (
            $donation->organization_id !== $organization->id
            || $donation->campaign_id !== $campaign->id
        ) {
            abort(404, __('The page could not be found'));
        }

        $donor = $donation->donor;
        return view('donations.success')->with(
            compact('organization', 'campaign', 'donation', 'donor')
        );
    }
}
