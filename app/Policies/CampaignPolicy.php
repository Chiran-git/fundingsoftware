<?php

namespace App\Policies;

use App\User;
use App\Campaign;
use App\Organization;
use Illuminate\Auth\Access\HandlesAuthorization;

class CampaignPolicy
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
     * Policy to determine if user can view any campaigns
     *
     * @param User $user
     * @param Organization $organization
     *
     * @return boolean
     */
    public function viewAny(User $user, Organization $organization)
    {
        if ($user->isSuperAdmin() || $user->isAppAdmin()) {
            return true;
        }

        // User type should be organization to update
        if (! $user->isOrganization()) {
            return false;
        }

        return $user->findAssociatedOrganization($organization->id) ? true : false;
    }

    /**
     * Determine whether the user can view the campaign.
     *
     * @param User $user
     * @param Campaign $campaign
     * @return mixed
     */
    public function view(User $user, Campaign $campaign)
    {
        if ($user->isSuperAdmin() || $user->isAppAdmin()) {
            return true;
        }

        // User type should be organization to update
        if (! $user->isOrganization()) {
            return false;
        }

        return $user->findAssociatedOrganization($campaign->organization_id) ? true : false;
    }

    /**
     * Determine whether the user can create campaign.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        if ($user->isSuperAdmin() || $user->isAppAdmin()) {
            return true;
        }

        // Find organizations of this user and see if user is owner or admin
        $userOrganization = $user->currentOrganization();

        if ($userOrganization && in_array($userOrganization->pivot->role, ['owner', 'admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can update campaign.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function update(User $user, Campaign $campaign, Organization $organization)
    {
        if ($campaign->organization_id != $organization->id) {
            return false;
        }

        // Find all organizations of this user and see if user is owner or admin or campaign-admin
        $userOrganization = $user->findAssociatedOrganization($campaign->organization_id);

        //check if user is campaign admin of the campaign to be updated
        if ($userOrganization &&
            (in_array($userOrganization->pivot->role, ['owner', 'admin']) ||
            (($userOrganization->pivot->role == 'campaign-admin') && ($user->findAssociatedOrganizationCampaign($campaign->id)))
            )) {
            return true;
        }

        return false;
    }
}
