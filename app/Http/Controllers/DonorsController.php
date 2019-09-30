<?php

namespace App\Http\Controllers;

use App\Donor;
use App\Organization;
use Illuminate\Http\Request;
use App\Exports\DonorsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Repositories\Contracts\DonorRepositoryInterface;

class DonorsController extends Controller
{

    /**
     * Method to display the donors listing
     *
     * @param Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $this->authorize('view', $request->user()->currentOrganization());

        return view('donations.donor-list');
    }

    /**
     * Method to display donors detail page
     *
     * @param \App\Organization $organization
     * @param \App\Donor        $donor
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Organization $organization, Donor $donor)
    {
        $this->authorize('view', $donor);
        return view('donations.donor-details', compact(['donor']));
    }

    /**
     * Method to export organization donors
     *
     * @param \App\Organization $organization
     * @param App\Repositories\Contracts\DonorRepositoryInterface $repo
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function export(Organization $organization, DonorRepositoryInterface $repo)
    {
        $this->authorize('view', $organization);

        $file_name = 'Donors-List-' . $organization->id . '.csv';

        return Excel::download(new DonorsExport($organization, $repo), $file_name);
    }

}
