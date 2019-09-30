<?php

namespace App\Repositories\Contracts;

interface InvitationRepositoryInterface
{
    /**
     * Method to find the organization invitation for the given code
     *
     * @param App\Organization $organizationId
     * @param string $code
     *
     * @return App\Invitation
     */
    public function findByCode($organizationId, $code);

    /**
     * Method to update all invitation of user
     *
     * @param array $conditions
     * @param array $data
     *
     * @return void
     */
    public function updateWithEmail($conditions, $data);

    /**
     * Method to find the organization invitation for the given code
     *
     * @param App\Organization $organizationId
     * @param string $code
     *
     * @return App\Invitation
     */
    public function findList($conditions, $value);

	/**
     * Method to find campaign-admins of organization
     *
     * @param App\Organization $organizationId
     * @param App\Campaign $campaignId
     *
     * @return App\OrganizationUser
     */
    public function findAllPendingCampaignAdmins($organizationId, $campaignId);
}
