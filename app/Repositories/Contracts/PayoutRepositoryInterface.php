<?php

namespace App\Repositories\Contracts;

interface PayoutRepositoryInterface
{
    /**
     * Get the payouts list for the given organization
     *
     * @param integer $organizationId
     *
     * @return App\Payout
     */
    public function getPayoutsList($organizationId);

}
