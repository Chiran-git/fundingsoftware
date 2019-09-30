<?php

namespace App\Repositories\Contracts;

interface DonationRepositoryInterface
{
    /**
     * Get the donations list for the given organization
     *
     * @param integer $organizationId
     *
     * @return App\Donation
     */
    public function getDonationsList($organizationId);
}
