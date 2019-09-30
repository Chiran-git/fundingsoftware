<?php

namespace App\Jobs\Organization;

use App\User;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\OrganizationUserRepositoryInterface;

class UpdateUser implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * User
     *
     * @var \App\User
     */
    public $user;

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
    public function __construct($organization, $user, $data)
    {
        $this->organization = $organization;
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        OrganizationUserRepositoryInterface $orgUserRepo,
        UserRepositoryInterface $userRepo
    ) {

        //Update in campaign users table using sync
        $campIds = [];
        foreach ($this->data->campaign_ids as $campaignId) {
            $campIds[$campaignId] = ['organization_id' => $this->organization->id ];
        }

        $this->user->campaigns()->sync($campIds);

        // Get and update the role of organization user in organization_users table
        $orgUser = $orgUserRepo->findWhere(
            [
                'organization_id' => $this->organization->id,
                'user_id' => $this->user->id
            ]);

        if (!$orgUser) {
            return false;
        }

        $updateRole = $orgUserRepo->update(
            $orgUser->id,
            [
                'role' => $this->data->role,
            ]
        );

        if (!$updateRole) {
            return false;
        }

        // Update the user in users table
        $updateUser = $userRepo->updateAccountUser($this->user->id, [
            'first_name' => $this->data->first_name,
            'last_name' => $this->data->last_name,
            'email' => $this->data->email
        ]);
    }
}
