<?php

namespace App\Repositories\Eloquent;

use App\OrganizationConnectedAccount;
use App\Repositories\Contracts\OrganizationConnectedAccountRepositoryInterface;

class OrganizationConnectedAccountRepository extends Repository implements OrganizationConnectedAccountRepositoryInterface
{
    /**
     * Method to update the connected account of the organization
     *
     * @param integer $id
     * @param \App\OrganizationConnectedAccount $organizationConnectedAccount
     *
     * @return boolean
     */
    public function updateConnectedAccount($id, $organizationConnectedAccount)
    {
        if ($organizationConnectedAccount) {
            return $this->update(
                $id,
                [
                    'nickname' => $organizationConnectedAccount['nickname'],
                    'is_default' => $organizationConnectedAccount['is_default']
                ]
            );
        }

        return false;
    }

    /**
     * Method to unassign the previous default account
     *
     * @param \App\OrganizationConnectedAccount $organizationConnectedAccount
     *
     * @return boolean
     */
    public function unassignPreviousDefaultAccount($organizationConnectedAccount)
    {
        $id = $organizationConnectedAccount->id;
        $orgId = $organizationConnectedAccount->organization_id;

        return OrganizationConnectedAccount::where('organization_id', $orgId)
            ->where('id', '<>', $id)
            ->update(['is_default' => 0]);
    }

    /**
     * Method to fetch latest bank details from Stripe
     * and update the connected account
     *
     * @param integer $id Organziation connected account id
     *
     * @return boolean
     */
    public function updateBankInfo($id)
    {
        $account = $this->find($id);

        // Find the bank account
        $externalAccounts = \Stripe\Account::allExternalAccounts(
            $account->stripe_user_id,
            [
              'limit' => 1,
            ]
        );

        $count = false;
        // Since we retrieved only 1 account above, this loop will run only once
        // The external account can either be bank or card
        foreach ($externalAccounts as $external) {
            $count = $this->update($id, [
                'external_account_object' => $external->object,
                'external_account_id' => $external->id,
                'external_account_name' => ($external->object == 'bank_account') ? $external->bank_name : $external->brand,
                'external_account_last4' => $external->last4,
            ]);
        }

        return $count;
    }

    /**
     * Method to find all organizaiton connected accounts
     *
     * @param App\Organization $organizationId
     *
     * @return App\OrganizationConnectedAccount
     */
    public function getAccountListing($organizationId)
    {
        // Get all admins and owner of organization
        return $this->model->where('organization_id', $organizationId)
            ->pluck('nickname', 'id');
    }

}
