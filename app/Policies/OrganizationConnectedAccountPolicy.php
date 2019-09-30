<?php

namespace App\Policies;

use App\User;
use App\Organization;
use App\OrganizationConnectedAccount;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationConnectedAccountPolicy
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
     * Determine if the account can be updated by the logged in organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return bool
     */
    public function create(User $user, Organization $organization)
    {
        if ($user->isSuperAdmin() || $user->isAppAdmin()) {
            return true;
        }

        // User type should be organization to create
        if (! $user->isOrganization()) {
            return false;
        }

        // Find all organizations of this user and see if user is owner or admin
        $userOrganization = $user->findAssociatedOrganization($organization->id);
        //check if user is owner of the account to be updated
        if ($userOrganization && (in_array($userOrganization->pivot->role, ['owner', 'admin']) )) {
            return true;
        }

        return false;
    }

    /**
     * Determine if the account can be updated by the logged in organization.
     *
     * @param  \App\User  $user
     * @param  \App\OrganizationConnectedAccount  $organizationConnectedAccount
     * @return bool
     */
    public function update(
        User $user,
        OrganizationConnectedAccount $organizationConnectedAccount,
        Organization $organization
    ) {
        if ($user->isSuperAdmin() || $user->isAppAdmin()) {
            return true;
        }

        // User type should be organization to update
        if (! $user->isOrganization()) {
            return false;
        }

        if ($organization->id === $organizationConnectedAccount->organization_id) {
            // Find all organizations of this user and see if user is owner or admin
            $userOrganization = $user->findAssociatedOrganization($organizationConnectedAccount->organization_id);
            //check if user is owner of the account to be updated
            if ($userOrganization && (in_array($userOrganization->pivot->role, ['owner', 'admin']) )) {
                return true;
            }
            return false;
        } else {
            return false;
        }
    }

    /**
     * Determine if the account can be deleted by the logged in organization.
     *
     * @param  \App\User  $user
     * @param  \App\OrganizationConnectedAccount  $organizationConnectedAccount
     * @return bool
     */
    public function delete(
        User $user,
        OrganizationConnectedAccount $organizationConnectedAccount,
        Organization $organization
    ) {
        return $this->update($user, $organizationConnectedAccount, $organization);
    }
}
