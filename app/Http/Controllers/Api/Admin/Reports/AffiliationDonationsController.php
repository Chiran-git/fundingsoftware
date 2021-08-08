<?php

namespace App\Http\Controllers\Api\Admin\Reports;

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
    public function index(Request $request)
    {
        $this->authorize('viewReport', $request->user());

        $data = $this->repo->getAffiliationDonationsForAdmin();
        return response()->json($data, 200);
    }
}
