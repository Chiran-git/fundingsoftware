<?php

namespace App\Jobs\Organization;

use App\Organization;
use Illuminate\Bus\Queueable;
use App\OrganizationConnectedAccount;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\OrganizationConnectedAccountRepositoryInterface;

class UpdateConnectedAccount implements ShouldQueue
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
     * Connected Account data
     *
     * @var array
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Organization $organization, 
        OrganizationConnectedAccount $organizationConnectedAccount, 
        $data
    )
    {
        $this->organization = $organization;
        $this->organizationConnectedAccount = $organizationConnectedAccount;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OrganizationConnectedAccountRepositoryInterface $repo)
    {
        //Update connected account info
        $updateAccount =  $repo->updateConnectedAccount(
            $this->organizationConnectedAccount->id,
            [
                'nickname' => $this->data['nickname'],
                'is_default'  => $this->data['is_default']
            ]
        );

        if ($updateAccount) {
            $count = OrganizationConnectedAccount::where('organization_id', $this->organizationConnectedAccount->organization_id)->get()->count();
            if ($count >= 2) {
                $unAssign = $repo->unassignPreviousDefaultAccount(
                    $this->organizationConnectedAccount
                );
                return $unAssign;
            } else {
                return $updateAccount;
            }
        }
    }
}
