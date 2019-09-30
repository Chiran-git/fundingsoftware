<?php

namespace App\Policies;

use App\User;
use App\Organization;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvitationPolicy
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
     * Determine whether the user can create invitation.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user, Organization $organization)
    {

        // Find organizations of this user and see if user is owner or admin
        $userOrganization = $user->findAssociatedOrganization($organization->id);

        if ($userOrganization && in_array($userOrganization->pivot->role, ['owner', 'admin'])) {
            return true;
        }

        return false;
    }
}
