<?php

namespace App\Http\Controllers\Admin;

use Auth;
use App\User;
use App\Campaign;
use App\Support\RJ;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CampaignsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', $request->user());
        return view('admin.campaigns');
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
        $this->authorize('viewAny', $request->user());

        if ($campaign) {
            $user = User::find($campaign->organization->owner_id);
            $impersonate = RJ::impersonateOrganization($user, $request);

            if ($impersonate) {
                return redirect("campaign/$campaign->id/details");
            } else {
                return redirect()->back();
            }
        }
    }

    /**
     * Method to create campaign
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Organization $organization, Request $request)
    {
        if ($organization) {
            $user = User::find($organization->owner_id);
            $impersonate = RJ::impersonateOrganization($user, $request);

            if ($impersonate) {
                return redirect()->route('campaign.create');
            } else {
                return redirect()->back();
            }
        }
    }
}
