<?php

namespace App\Jobs\Organization;

use App\Organization;
use Illuminate\Bus\Queueable;
use App\OrganizationConnectedAccount;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\Organization\ConnectedAccountWasDeleted;
use App\Repositories\Contracts\OrganizationConnectedAccountRepositoryInterface;

class DeleteConnectedAccount implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization
     *
     * @var \App\Organization
     */
    public $organization;

    /**
     * Organization Connected Account
     *
     * @var \App\OrganizationConnectedAccount
     */
    public $organizationConnectedAccount;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Organization $organization, 
        OrganizationConnectedAccount $organizationConnectedAccount
    )
    {
        $this->organization = $organization;
        $this->organizationConnectedAccount = $organizationConnectedAccount;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OrganizationConnectedAccountRepositoryInterface $repo)
    {

        // Deauthorization request
        if ($accountId = $this->organizationConnectedAccount->stripe_user_id) {
            
            try {
                \Stripe\OAuth::deauthorize([
                    'stripe_user_id' => $accountId,
                ]);
            } catch (\Stripe\Error\OAuth\OAuthBase $e) {
                abort(400, $e->getMessage());
            }

        }
        
        //delete organization connected account
        if ($repo->delete($this->organizationConnectedAccount->id)) {
            //update payout_method of all the related campaigns
            $this->organizationConnectedAccount->campaigns()->update([
                'payout_method' => 'check',
                'payout_connected_account_id' => null
            ]);
            
            event(new ConnectedAccountWasDeleted($this->organizationConnectedAccount));

            return true;
        }
    }
}