<?php

namespace App\Http\Controllers;

use App\Organization;
use Illuminate\Http\Request;
use App\Http\Resources\InvitationResource;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\InvitationRepositoryInterface;

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

    public function __construct(InvitationRepositoryInterface $repo, UserRepositoryInterface $userRepo)
    {
        $this->repo = $repo;
        $this->userRepo = $userRepo;
    }

    /**
     * Display the invitation page
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\View\View
     */
    public function accept(Organization $organization, $code)
    {
        //get invitation by code
        if (!$invitation = $this->repo->findByCode($organization->id, $code)) {
            return redirect()->route('login')->with('errorMessage', __('Invalid invitation.'));
        }
        //check if invitation already accepted
        if (!empty($invitation->accepted_at) || !empty($invitation->user_id)) {
            return redirect()->route('login')->with('errorMessage', __('Invitation already accepted.'));
        }
        //check if invitation is expires
        if ($invitation->expires_at < now()) {
            return redirect()->route('login')->with('errorMessage', __('Invitation expired.'));
        }
        $setPassword = true;
        //check exiting user
        if ($user = $this->userRepo->findWhere(['email' => $invitation->email])) {
            $setPassword = false;
            // Find all organizations of this user and see if user is owner or admin
            $userOrganization = $user->findAssociatedOrganization($organization->id);
            //check if user is owner or admin of the account
            if ($userOrganization && (($userOrganization->pivot->role == 'owner') ||
                    ($invitation->role != "owner" && $userOrganization->pivot->role == 'admin'))) {
                return redirect()->route('login')->with('infoMessage', __('Login to manage campaign.'));
            }
        }
        $invitation = new InvitationResource($invitation);
        return view('invitation.accept', compact('organization', 'code', 'invitation', 'user', 'setPassword'));
    }
}
