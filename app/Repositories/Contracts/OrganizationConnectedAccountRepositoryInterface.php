<?php

namespace App\Repositories\Contracts;

interface OrganizationConnectedAccountRepositoryInterface
{
    /**
     * Method to update the connected account of the organization
     *
     * @param integer $id
     * @param \App\OrganizationConnectedAccount $organizationConnectedAccount
     *
     * @return boolean
     */
    public function updateConnectedAccount($id, $organizationConnectedAccount);

    /**
     * Method to unassign the previous default account
     *
     * @param \App\OrganizationConnectedAccount $organizationConnectedAccount
     *
     * @return boolean
     */
    public function unassignPreviousDefaultAccount($organizationConnectedAccount);

    /**
     * Method to fetch latest bank details from Stripe
     * and update the connected account
     *
     * @param integer $id Organziation connected account id
     *
     * @return boolean
     */
    public function updateBankInfo($id);
}
