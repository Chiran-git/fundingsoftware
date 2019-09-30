<?php

namespace App\Http\Controllers;

use View;
use App\Campaign;
use App\Organization;
use Illuminate\Http\Request;

class CampaignsController extends Controller
{
    public function __construct()
    {
        View::share(['menuItem'=> 'campaign']);
    }

    /**
     * Action to list all campaigns
     *
     * @param Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', [Campaign::class, $request->user()->currentOrganization()]);

        return view('campaign.index');
    }

    /**
     * Method to view the edit campaign page
     *
     * @param \App\Campaign $campaign
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function edit(Request $request, Campaign $campaign)
    {
        $this->authorize('update', [$campaign, $request->user()->currentOrganization()]);

        return view('campaign.edit-campaign', compact('campaign'));
    }

    /**
     * Method to create campaign
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        $this->authorize('create', 'App\Campaign');

        return view('campaign.create');
    }

    /**
     * Method to view the campaign page
     *
     * @param \App\Campaign $campaign
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function details(Request $request, Campaign $campaign)
    {
        $this->authorize('view', $campaign);

        if ($campaign->published_at) {
            return view('campaign.admin-details')->with(compact('campaign'));
        } else {
            return redirect()->route('campaign.create', ['step'=>2, 'id'=>$campaign->id]);
        }
    }

    /**
     * Method to show/view a campaign
     * This action is generally viewed by the Donor to make the donation.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Organization $organization
     * @param \App\Campaign $campaign
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Organization $organization, Campaign $campaign)
    {        
        //show only active campaigns to donor
        if (! auth()->user() && ! empty($campaign->end_date) && $campaign->end_date < now()) {
            return view('campaign.public-campaign-deactivated')->with(compact('organization', 'campaign'));    
        }
        return view('campaign.show-donor')->with(compact('organization', 'campaign'));
    }

    /**
     * Method to initiate a campaign donation
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Organization $organization
     * @param \App\Campaign $campaign
     *
     * @return \Illuminate\View\View
     */
    public function donate(Request $request, Organization $organization, Campaign $campaign)
    {
        return view('campaign.donate')->with(compact('organization', 'campaign'));
    }
}
