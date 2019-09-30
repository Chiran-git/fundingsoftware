<?php

namespace App\Http\Controllers\Admin;

use App\Campaign;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the dashboard
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function show(Request $request)
    {
        return view('admin.dashboard');
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
            ->select('id as originalId', 'name')
            ->get();

        $campaigns = Campaign::active()
            ->where('name', 'like', "%{$request->q}%")
            ->select('id as originalId', 'name')
            ->get();

        foreach ($campaigns as $campaign) {
            $campaign->type = "Campaign";
        }

        foreach ($organizations as $organization) {
            $organization->type = "Organization";
            $campaigns->add($organization);
        }

        return $campaigns;
    }

    /**
     * Show the admin profile page
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function getMyAccount(Request $request)
    {
        return redirect()->route('myaccount');
    }

    /**
     * Show the admin change password page
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function getChangePassword(Request $request)
    {
        return redirect()->route('change-password');
    }
}
