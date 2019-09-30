<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\InvitationRepositoryInterface;

class InvitationRepository extends Repository implements InvitationRepositoryInterface
{

    /**
     * Method to find the organization invitation for the given code
     *
     * @param App\Organization $organizationId
     * @param string $code
     *
     * @return App\Invitation
     */
    public function findByCode($organizationId, $code)
    {
        // Get invitation
        return $this->findWhere([
                'organization_id' => $organizationId,
                'code' => $code
            ]);
    }

    /**
     * Method to update all invitation of user
     *
     * @param array $conditions
     * @param array $data
     *
     * @return void
     */
    public function updateWithEmail($conditions, $data)
    {
        return $this->model->where($conditions)->update($data);
    }

    /**
     * Method to find the organization invitation for the given code
     *
     * @param App\Organization $organizationId
     * @param string $code
     *
     * @return App\Invitation
     */
    public function findList($conditions, $value)
    {
        // Get campaign list
        $data = $this->model->where($conditions)->pluck($value)->toArray();
        $flattened = array_flatten($data);
        $unique    = array_unique($flattened);

        return $unique;
    }

    /**
     * Method to find campaign admins of organization
     *
     * @param App\Organization $organizationId
     * @param App\Campaign $campaignId
     *
     * @return App\OrganizationUser
     */
    public function findAllPendingCampaignAdmins($organizationId, $campaignId)
    {
        // Get all campaign admins of organization
        return $this->model->where('organization_id', $organizationId)
                ->whereNull('accepted_at')
                ->where(function ($query) use ($campaignId) {
                    $query->whereJsonContains('campaign_ids', $campaignId)
                        ->orWhereIn('role', ['admin', 'owner']);
                })
                ->select(['first_name', 'last_name', 'email', 'role'])
                ->get();
    }

    /**
     * Method to find campaign admins of organization
     *
     * @param App\Organization $organizationId
     *
     * @return App\OrganizationUser
     */
    public function findAllPendingOrganizationUsers($organizationId)
    {
        // Get all pending campaign users of organization
        return $this->model->where('invitations.organization_id', $organizationId)
                ->whereNull('accepted_at')
                ->select(['id', 'first_name', 'last_name', 'email', 'role', 'no_of_resends', 'campaign_ids'])
                ->orderBy('first_name')
                ->orderBy('last_name')
                ->get();
    }

}
