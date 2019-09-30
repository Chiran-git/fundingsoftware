<?php

namespace App\Policies;

use App\User;
use App\Organization;
use Illuminate\Auth\Access\HandlesAuthorization;

class OrganizationPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function view(User $user, Organization $organization)
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
     * Determine whether the user can create organizations.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(?User $user)
    {
        // Anyone can create organization
        return true;
    }

    /**
     * Determine whether the user can update the organization.
     *
     * Organization can only be updated by super admin or org owner org admin
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function update(User $user, Organization $organization)
    {
        if ($user->isSuperAdmin() || $user->isAppAdmin()) {
            return true;
        }

        // User type should be organization to update
        if (! $user->isOrganization()) {
            return false;
        }

        // Find all organizations of this user and see if user is owner or admin
        $userOrganization = $user->findAssociatedOrganization($organization->id);

        if ($userOrganization && in_array($userOrganization->pivot->role, ['owner', 'admin'])) {
            return true;
        }

        return false;
    }

    /**
     * Determine whether the user can delete the organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function delete(User $user, Organization $organization)
    {
        return $this->update($user, $organization);
    }

    /**
     * Determine whether the user can restore the organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function restore(User $user, Organization $organization)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the organization.
     *
     * @param  \App\User  $user
     * @param  \App\Organization  $organization
     * @return mixed
     */
    public function forceDelete(User $user, Organization $organization)
    {
        //
    }
}
