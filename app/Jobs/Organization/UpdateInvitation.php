<?php

namespace App\Jobs\Organization;

use App\Invitation;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\InvitationRepositoryInterface;

class UpdateInvitation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * Invitation
     *
     * @var \App\Invitation
     */
    public $invitation;

    /**
     * Input data variable
     *
     * @var raay
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, Invitation $invitation, $data)
    {
        $this->organization = $organization;
        $this->invitation = $invitation;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param \App\Repositories\Contracts\InvitationRepositoryInterface $repo
     *
     * @return void
     */
    public function handle(
        InvitationRepositoryInterface $invitationRepo,
        UserRepositoryInterface $userRepo
        ) {

        //set data to create users
        $data = [
            'first_name' => $this->invitation->first_name,
            'last_name' => $this->invitation->last_name,
            'email' => $this->invitation->email,
        ];
        if (!empty($this->data['password'])) {
            $data['password'] = Hash::make($this->data['password']);
        }
        // First create the User if not already exists
        $user = $userRepo->createIfNotExists($data);

        //set conditions
        $conditions = [
            'organization_id' => $this->organization->id,
            'email' => $this->invitation->email,
            'accepted_at' => null,
        ];
        //get user role
        $role = $this->invitation->role;

        $inviteOwnerCondition = $inviteAdminCondition = $conditions;
        $inviteOwnerCondition['role'] = 'owner';
        $inviteAdminCondition['role'] = 'admin';

        //check role
        if ($role != 'owner' && $invitationRepo->findWhere($inviteOwnerCondition)) {
            $role = 'owner';
        } elseif ($role != 'admin' && $invitationRepo->findWhere($inviteAdminCondition)) {
            $role = 'admin';
        }

        $userRole = "";
        //check if user is associated with organization
        if ($userOrganization = $user->findAssociatedOrganization($this->organization->id)) {
            $userRole = $userOrganization->pivot->role;

            if (($userRole != 'owner') && ($userRole != $role) && ($userRole != 'admin' || $role == 'owner')) {

                // Attach the user with the organization
                $user->organizations()->updateExistingPivot($this->organization->id, ['role' => $role]);
            }

        } else {
            // Attach the user with the organization
            $user->organizations()->attach($this->organization->id, ['role' => $role]);
        }

        if ($role == 'campaign-admin') {
            //get campaigns list
            if ($campaignIds = $invitationRepo->findList($conditions, 'campaign_ids')) {

                //attach user with campaigns for given organization
                foreach ($campaignIds as $campaignId) {
                    $user->campaigns()->syncWithoutDetaching([$campaignId => ['organization_id' => $this->organization->id]]);
                }
            }

        }

        // Update invitation
        $update = $invitationRepo->updateWithEmail(
            $conditions,
            [
                'accepted_at' => now(),
                'user_id' => $user->id
            ]);

        return $user;
    }
}
