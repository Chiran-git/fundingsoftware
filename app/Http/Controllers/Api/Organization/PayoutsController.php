<?php

namespace App\Http\Controllers\Api\Organization;

use App\Payout;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\PayoutRepositoryInterface;

class PayoutsController extends Controller
{
    /**
     * Get the listing of all the payouts
     *
     * @param \App\Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(
        Organization $organization,
        PayoutRepositoryInterface $repo
    ) {
        return response()->json($repo->getPayoutsList($organization->id, config('pagination.limit')));
    }
}
