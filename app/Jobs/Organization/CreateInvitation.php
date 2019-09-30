<?php

namespace App\Jobs\Organization;

use App\User;
use App\Organization;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\Organization\InvitationWasCreated;
use App\Repositories\Contracts\InvitationRepositoryInterface;

class CreateInvitation implements ShouldQueue
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
     * organization data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization, User $user, $data)
    {
        $this->organization = $organization;
        $this->user = $user;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param \App\Repositories\Contracts\InvitationRepositoryInterface $repo
     *
     * @return void
     */
    public function handle(InvitationRepositoryInterface $repo)
    {
        $data = [
            'organization_id' => $this->organization->id,
            'invitee_id' => $this->user->id,
            'first_name' => $this->data['first_name'],
            'last_name' => ! empty($this->data['last_name']) ? $this->data['last_name'] : null,
            'email' => $this->data['email'],
            'role' => $this->data['role'],
            'code' => Str::random(10),
            'expires_at' => date('Y-m-d H:i:s', strtotime("+7 days")),
        ];
        $data['campaign_ids'] = [];
        if (! empty($this->data['campaign_ids']) && $this->data['role'] == 'campaign-admin') {
            $data['campaign_ids'] = $this->data['campaign_ids'];
        }
        // First create the User if not already exists
        $invitation = $repo->store($data);

        if ($invitation) {
            //call invitation created event
            event(new InvitationWasCreated($invitation));

            return $invitation;
        }

        return false;
    }
}
