<?php

namespace App\Repositories\Contracts;

interface AffiliationRepositoryInterface
{
    /**
     * Method to get affiliation donations for a particular organization
     */
    function getAffiliationDonationsByOrganization($organization);

    /**
     * Method to get affiliation donations of all organizations for superadmin
     */
    function getAffiliationDonationsForAdmin();
}
