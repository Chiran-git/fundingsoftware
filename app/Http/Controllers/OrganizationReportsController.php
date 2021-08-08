<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrganizationReportsController extends Controller
{
    /**
     * Method to return the donation affiliation page
     * @param Request $request
     * @return Illuminate\View\View
     */
    public function affiliationReports(Request $request)
    {
        return view('organization.reports.affiliation-donations');
    }
}
