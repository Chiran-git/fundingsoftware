<?php

namespace App\Repositories\Contracts;

interface OrganizationUserRepositoryInterface
{
    /**
     * Method to find owner and admins of organization
     *
     * @param App\Organization $organizationId
     *
     * @return App\OrganizationUser
     */
    public function findAllAdmins($organizationId);

    /**
     * Method to find all users organization
     *
     * @param App\Organization $organizationId
     *
     * @return App\OrganizationUser
     */
    public function findAllUsers($organizationId);
}
