<?php

namespace App\Http\Controllers\Api\Organization;

use App\Invitation;
use App\Organization;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\InvitationResource;
use App\Http\Resources\InvitationDetailResource;
use App\Events\Organization\InvitationWasCreated;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Http\Requests\Organization\ValidateEmailRequest;
use App\Http\Requests\Organization\StoreInvitationRequest;
use App\Http\Requests\Organization\UpdateInvitationRequest;
use App\Repositories\Contracts\InvitationRepositoryInterface;
use App\Jobs\Organization\CreateInvitation as CreateInvitationJob;
use App\Jobs\Organization\UpdateInvitation as UpdateInvitationJob;
use App\Jobs\Organization\UpdateNoOfResend as UpdateNoOfResendInvitationJob;

class InvitationsController extends Controller
{
    /**
     * @var InvitationRepositoryInterface
     */
    private $repo;

    /**
     * @var UserRepositoryInterface
     */
    private $userRepo;

    /**
     * @param InvitationRepositoryInterface $userRepo
     */
    public function __construct(InvitationRepositoryInterface $repo, UserRepositoryInterface $userRepo)
    {
        $this->repo = $repo;
        $this->userRepo = $userRepo;
    }

    /**
     * Method to create invitation for user to join organization
     *
     * @param Organization $organization
     * @param user $user
     * @param StoreInvitationRequest $request
     * @return void
     */
    public function store(
        Organization $organization,
        StoreInvitationRequest $request
    ) {
        //check exiting user
        if ($response = $this->checkExistingUser($organization, $request->input('email'))) {
            if (($response['role'] == 'owner') || ($request->input('role') != "owner" && $response['role'] == 'admin')) {
                $response['message'] = 'User already has admin access';
                return response()->json($response);
            }
        }
        $user = auth()->user();
        $invitation = dispatch_now(new CreateInvitationJob($organization, $user, $request->all()));

        return response()->json(new InvitationResource($invitation));
    }

    /**
     * Method to show invitation data
     *
     * @param \App\Organization $organization
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Organization $organization, $code)
    {
        //get invitation by code
        if ($invitation = $this->repo->findByCode($organization->id, $code)) {
            return response()->json(new InvitationDetailResource($invitation));
        }
        return response()->json(['message' => __('No invitation found for the organization with the given code.')], 400);
    }

    /**
     * Method to accept invitation and
     *
     * @param Organization $organization
     * @param string $code
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Organization $organization, UpdateInvitationRequest $request, $code)
    {
        //get invitation by code
        if ($invitation = $this->repo->findByCode($organization->id, $code)) {

            //check invitation expires date
            if ($invitation->expires_at < date('Y-m-d H:i:S')) {
                return response()->json(['message' => __('Invitation expired.')], 400);
            }

            //call job to update invitation
            if (empty($invitation->accepted_at) &&
                ($user = dispatch_now(new UpdateInvitationJob($organization, $invitation, $request->input())))) {

                return response()->json(new UserResource($user));
            } else {
                return response()->json(['message' => __('Invitation has already accepted.')], 400);
            }
        }
        return response()->json(['message' => __('No invitation found for the organization with the given code.')], 400);
    }

    /**
     * Method to user has joined organization
     *
     * @param Organization $organization
     * @param ValidateEmailRequest $request
     * @return void
     */
    public function checkEmail(
        Organization $organization,
        ValidateEmailRequest $request
    ) {
        //check exiting user
        if ($response = $this->checkExistingUser($organization, $request->input('email'))) {
            return response()->json($response);
        }

        return response()->json([]);
    }

    /**
     * Method to check existing user
     *
     * @param Organization $organization
     * @param string $email
     *
     * @return json
     */
    private function checkExistingUser(Organization $organization, $email)
    {
        //check exiting user
        if ($existingUser = $this->userRepo->findWhere(['email' => $email])) {
            if ($userOrganization = $existingUser->findAssociatedOrganization($organization->id)) {

                $response = [
                    'user' => new UserResource($existingUser),
                    'role' => $userOrganization->pivot->role
                ];
                return $response;
            }
        }
    }

    /**
     * Method to resend invitation email to user
     *
     * @param Organization $organization
     * @param Invitation $invitation
     *
     * @return void
     */
    public function resendEmail(
        Organization $organization,
        Invitation $invitation
    ) {
        $this->authorize('update', $organization);

        //call invitation created event
        if (event(new InvitationWasCreated($invitation)) ) {
            dispatch_now( new UpdateNoOfResendInvitationJob($invitation) );
        }

        return response()->json([]);
    }

    /**
     * Method to delete donor question for the given organization
     *
     * @param Organization $organization
     * @param Invitation   $invitation
     *
     * @return void
     */
    public function destroy(
        Organization $organization,
        Invitation $invitation
    ) {
        $this->authorize('delete', $organization);

        // Delete invitation
        if ($this->repo->delete($invitation->id)) {
            return response()->json([]);
        }
        return response()->json(['message' => __('Unable to delete invited user')], 400);
    }

}
