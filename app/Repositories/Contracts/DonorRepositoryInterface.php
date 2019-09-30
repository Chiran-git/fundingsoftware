<?php

namespace App\Repositories\Contracts;

interface DonorRepositoryInterface
{
    /**
     * Get the donors list for the given organization
     *
     * @param integer $organizationId
     *
     * @return App\Donor
     */
    public function getDonorsList($organizationId);

    /**
     * Method to create a new donor if the donor with given email doesn't exist
     *
     * @param array $attributes Donor attributes
     *
     * @return App\Donor New Donor or existing user object
     */
    function createIfNotExists($attributes);
}
