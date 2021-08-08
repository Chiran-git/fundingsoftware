<?php

namespace App\Jobs\Organization;

use App\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\InvitationRepositoryInterface;

class UpdateNoOfResend implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Invitation
     *
     * @var \App\Invitation
     */
    public $invitation;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(InvitationRepositoryInterface $invitationRepo)
    {
        // Update the no of resends and expiry date
        return $invitationRepo->update(
            $this->invitation->id,
            [
                'expires_at' => date('Y-m-d H:i:s', strtotime("+7 days")),
                'no_of_resends' => $this->invitation->no_of_resends + 1
            ]
        );
    }
}
