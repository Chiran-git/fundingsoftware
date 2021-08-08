<?php

namespace App\Http\Controllers\Api\Organization\Reports;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\AffiliationRepositoryInterface;

class AffiliationDonationsController extends Controller
{
    protected $repo;

    /**
     * Constructor
     */
    public function __construct(AffiliationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Organization $organization, Request $request)
    {
        $this->authorize('view', $organization);
        $data = $this->repo->getAffiliationDonationsByOrganization($organization);
        return response()->json($data, 200);
    }
}
