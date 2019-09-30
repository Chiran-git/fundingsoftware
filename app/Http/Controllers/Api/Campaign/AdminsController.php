<?php

namespace App\Http\Controllers\Api\Campaign;

use App\User;
use App\Campaign;
use App\Organization;
use App\CampaignUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\CampaignUserResource;
use App\Http\Resources\OrganizationUserResource;
use App\Repositories\Contracts\InvitationRepositoryInterface;
use App\Repositories\Contracts\OrganizationUserRepositoryInterface;

class AdminsController extends Controller
{
    /**
     * @var OrganizationUserRepositoryInterface
     */
    private $orgUserRepo;

    /**
     * @var InviteeRepositoryInterface
     */
    private $invitationRepo;

    /**
     * @param OrganizationUserRepositoryInterface $userRepo
     */
    public function __construct(
        OrganizationUserRepositoryInterface $orgUserRepo,
        InvitationRepositoryInterface $invitationRepo
    ) {
        $this->orgUserRepo = $orgUserRepo;
        $this->invitationRepo = $invitationRepo;
    }

    /**
     * Method to list organization owner, campaign admins, admins
     *
     * @param Campaign $campaign
     * @param Organization $organization
     * @return void
     */
    public function index(Organization $organization, Campaign $campaign) {
        $this->authorize('view', $organization);

        //get list of admins and owner of organization
        $orgAdmins = $this->orgUserRepo->findAllAdmins($organization->id);
        $orgAdminIds = $orgAdmins->pluck('user_id');

        // Get the campaign users
        $campaignUsers = $campaign->users()
            ->whereNotIn('user_id', $orgAdminIds)
            ->get();

        //Get the invitees
        $invitees = $this->invitationRepo->findAllPendingCampaignAdmins($organization->id, $campaign->id);

        return response()->json(
            $this->respondWithCampaignAdmin($orgAdmins, $campaignUsers, $invitees)
        );
    }

    private function respondWithCampaignAdmin($orgAdmins, $campaignUsers, $invitees)
    {
        $users = collect([]);

        $users = $users->merge($this->getUsersFromOrgUserCollection($orgAdmins));

        $users = $users->merge($this->getUsersFromCampaignUsersCollection($campaignUsers));

        $users = $users->merge($this->getUsersFromInviteesCollection($invitees));

        return $users;
    }

    private function getUsersFromOrgUserCollection($orgAdmins)
    {
        $return = collect();

        foreach ($orgAdmins as $orgUser) {
            $return->push([
                'id' => $orgUser->user_id,
                'first_name' => $orgUser->user->first_name,
                'last_name' => $orgUser->user->last_name,
                'email' => $orgUser->user->email,
                'role' => $orgUser->role,
            ]);
        }

        return $return;
    }

    private function getUsersFromCampaignUsersCollection($campaignUsers)
    {
        $return = collect();

        foreach ($campaignUsers as $campaignUser) {
            $return->push([
                'id' => $campaignUser->id,
                'first_name' => $campaignUser->first_name,
                'last_name' => $campaignUser->last_name,
                'email' => $campaignUser->email,
                'role' => 'campaign-admin',
            ]);
        }

        return $return;
    }

    private function getUsersFromInviteesCollection($invitees)
    {
        $return = collect();

        foreach ($invitees as $invitee) {
            $return->push([
                'id' => null,
                'first_name' => $invitee->first_name,
                'last_name' => $invitee->last_name,
                'email' => $invitee->email,
                'role' => $invitee->role,
            ]);
        }

        return $return;
    }
}
