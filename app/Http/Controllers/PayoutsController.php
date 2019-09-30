<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PayoutsController extends Controller
{

    /**
     * Method to display the payouts history
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('view', $request->user()->currentOrganization());

        return view('payouts.index');
    }

    /**
     * Get the listing of connected accounts to generate dropdown
     *
     * @param \App\Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Organization $organization)
    {
        $this->authorize('view', $organization);

        $campaigns = $this->repo->getCampaignListing($organization->id);

        return response()->json($campaigns);
    }
}
