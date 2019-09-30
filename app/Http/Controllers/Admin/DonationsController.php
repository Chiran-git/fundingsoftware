<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Campaign;
use App\Support\RJ;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class DonationsController extends Controller
{
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
                return redirect()->route('donation.create', ['organization' => $organization->id]);
            } else {
                return redirect()->back();
            }
        }
    }
}
