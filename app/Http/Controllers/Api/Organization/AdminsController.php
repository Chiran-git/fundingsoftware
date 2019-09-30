<?php

namespace App\Http\Controllers\Api\Organization;

use App;
use App\User;
use App\Organization;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrganizationUserResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\InvitationRepositoryInterface;
use App\Repositories\Contracts\OrganizationUserRepositoryInterface;

class AdminsController extends Controller
{
    /**
     * @var OrganizationUserRepositoryInterface
     */
    private $repo;

    /**
     * @var InviteeRepositoryInterface
     */
    private $invitationRepo;

    /**
     * @param OrganizationUserRepositoryInterface $userRepo
     */
    public function __construct(
        OrganizationUserRepositoryInterface $repo,
        InvitationRepositoryInterface $invitationRepo
    ) {
        $this->repo = $repo;
        $this->invitationRepo = $invitationRepo;
    }

    /**
     * Method to list organization owner and admins
     *
     * @param Organization $organization
     * @return void
     */
    public function index(Organization $organization) {
        $this->authorize('view', $organization);

        //get list of admins and owner of organization
        return OrganizationUserResource::collection(
            $this->repo->findAllAdmins($organization->id)
        );
    }

    /**
     * Method to get the account users
     *
     * @param \App\Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function accountUsers(Organization $organization)
    {
        $this->authorize('view', $organization);

        //get list of admins and owner of organization
        return OrganizationUserResource::collection(
            $this->repo->findAllUsers($organization->id)
                ->sortBy('user.first_name')
                ->sortBy('user.last_name')
        );
    }

    /**
     * Method to get the account users
     *
     * @param \App\Organization $organization
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pendingAccountUsers(Organization $organization)
    {
        $this->authorize('view', $organization);

        // Get the list of invited users.
        $invitees = $this->invitationRepo->findAllPendingOrganizationUsers($organization->id);

        if ($invitees) {
            foreach ($invitees as $key => $invitee) {
                if ($invitee->role == 'campaign-admin') {
                    $campaigns = App\Campaign::whereIn('id', $invitee->campaign_ids)
                        ->pluck('name');
                    $invitees[$key]['campaigns'] =  $campaigns;
                }
            }
        }

        return response()->json($invitees);
    }

    /**
     * Method to delete donor question for the given organization
     *
     * @param Organization $organization
     * @param User         $user
     *
     * @return void
     */
    public function destroy(
        Organization $organization,
        User $user,
        UserRepositoryInterface $userRepo
    ) {
        $this->authorize('delete', $organization);

        // Delete invitation
        if ($userRepo->delete($user->id)) {
            $this->repo->findWhere([
                'organization_id' => $organization->id,
                'user_id' => $user->id
                ])
                ->delete();
            return response()->json([]);
        }
        return response()->json(['message' => __('Unable to delete user')], 400);
    }

}
