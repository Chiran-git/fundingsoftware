<?php

namespace App\Http\Controllers;

use Auth;
use App\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Http\Resources\OrganizationSearchResource;

class OrganizationsController extends Controller
{
    /**
     * Display the account setup page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function accountSetup(Request $request)
    {
        // Redirect to dashboard if account has already been setup
        if ($request->user()->organization->setup_completed) {
            return redirect()->route('dashboard');
        }

        return view('organization.setup-account');
    }

    /**
     * Display the organization create
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function create(Request $request)
    {
        return view('organization.setup-account');
    }

    public function edit(Request $request)
    {
        $this->authorize('update', $request->user()->organization);

        return view('organization.edit');
    }

    /**
     * Method to show/view an organization page
     * This action is generally viewed by the Donor and is the landing page
     * to make the donation.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Organization $organization
     *
     * @return \Illuminate\View\View
     */
    public function show(Request $request, Organization $organization)
    {
        return view('organization.show')->with(compact('organization'));
    }

    /**
     * Method to search organizations (from autocomplete)
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // If less than 3 chars, do not perform a search
        if (strlen($request->q) < 3) {
            return response()->json([]);
        }

        // Find 10 active organizations with the given search query
        $organizations = Organization::active()
            ->where('name', 'like', "%{$request->q}%")
            ->limit(10)
            ->get();

        return OrganizationSearchResource::collection($organizations);
    }
}
