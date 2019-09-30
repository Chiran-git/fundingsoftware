<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\OrganizationUserRepositoryInterface;

class OrganizationUserRepository extends Repository implements OrganizationUserRepositoryInterface
{
    /**
     * Method to find owner and admins of organization
     *
     * @param App\Organization $organizationId
     *
     * @return App\OrganizationUser
     */
    public function findAllAdmins($organizationId)
    {
        // Get all admins and owner of organization
        return $this->model->where('organization_id', $organizationId)
            ->whereIn('role', ['owner', 'admin'])
            ->get();

    }

    /**
     * Method to find all users organization
     *
     * @param App\Organization $organizationId
     *
     * @return App\OrganizationUser
     */
    public function findAllUsers($organizationId)
    {
        // Get all admins and owner of organization
        return $this->model->where('organization_id', $organizationId)
            ->get();
    }
}
