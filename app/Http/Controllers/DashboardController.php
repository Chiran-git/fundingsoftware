<?php

namespace App\Http\Controllers;

use App\User;
use App\Support\RJ;
use App\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DashboardController extends Controller
{

    private $manager;

    /**
     * Show the dashboard
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function show(Request $request)
    {
        if ($request->user()->isSuperAdmin() || $request->user()->isAppAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('dashboard');
    }

    /**
     * Show the my profile page
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function getMyProfile(Request $request)
    {
        if (Session::get('impersonated_by')) {
            $this->removeImpersonate($request);
        }
        return view('auth.myaccount');
    }

    /**
     * Show the change password page
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function getChangePassword(Request $request)
    {
        if (Session::get('impersonated_by')) {
            $this->removeImpersonate($request);
        }
        return view('auth.change-password');
    }

    /**
     * Show the superadmin dash
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\View\View
     */
    public function superAdminDashBoard()
    {
        return view('admin.dashboard');
    }

    /**
     * @param Organization $organization
     * @param Illuminate\Http\Request $request
     */
    public function impersonate (Organization $organization, Request $request)
    {
        if ($organization) {
            $user = User::find($organization->owner_id);
            $impersonate = RJ::impersonateOrganization($user, $request);

            if ($impersonate) {
                return redirect('/dashboard');
            } else {
                return redirect()->back();
            }
        }
    }

    /**
     * @param Illuminate\Http\Request $request
     *
     * @return null
     */
    public function impersonateLeave (Request $request)
    {
        $this->removeImpersonate($request);
        $request->session()->flush();
        return redirect('/organizations');
    }

    /**
     * @param Organization $organization
     * @param Illuminate\Http\Request $request
     *
     * @return null
     */
    private function removeImpersonate(Request $request)
    {
        Auth::user()->leaveImpersonation();
        $request->session()->forget(['impersonator_name', 'impersonator_role']);
    }
}
