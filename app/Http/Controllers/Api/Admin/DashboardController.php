<?php

namespace App\Http\Controllers\Api\Admin;

use App\Donation;
use App\Campaign;
use App\Support\RJ;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
	/**
     * Get the admin statistics
     *
     * @param Request
     *
     * @return \Illuminate\Http\JsonResponse
     */
	public function stats(Request $request)
	{
		$this->authorize('viewAny', $request->user());

		$allTimeDonation = Campaign::sum('funds_raised');

		$activeOrganization = Organization::active()->count();

		$activeCampaigns = Campaign::active()->count();

		$completeCampaigns = Campaign::complete()->count();

		$data = [
					'allTimeDonation' => RJ::donationMoney($allTimeDonation/100),
					'activeOrganization' => $activeOrganization,
					'activeCampaigns' => $activeCampaigns,
					'completeCampaigns' => $completeCampaigns
				];
		return response()->json($data, 200);
	}
}
