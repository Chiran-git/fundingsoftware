<?php

namespace App\Policies;

use App\User;
use App\Donor;
use App\CampaignUser;
use Illuminate\Auth\Access\HandlesAuthorization;

class DonorPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine whether the user can view donor.
     *
     * @param  \App\User  $user
     * @param \App\Donor  $donor
     *
     * @return mixed
     */
    public function view(User $user, Donor $donor)
    {
        if ($user->isSuperAdmin() || $user->isAppAdmin()) {
            return true;
        }

        // Find all organizations of this user and see if user is owner or admin or campaign-admin
        $organization = $user->currentOrganization();

        if ($user->currentRole() == 'owner') {
            return $donor->isOrganizationDonor($organization->id);
        } else if ($user->currentRole() == 'campaign-admin') {
            $campaignIds = CampaignUser::where('organization_id', $organization->id)
                ->where('user_id', $user->id)
                ->pluck('campaign_id')->toArray();

            if (! empty($campaignIds)) {
                return $donor->isOrganizationCampaignDonor($organization->id, $campaignIds);
            }
        }

        return false;
    }
}
