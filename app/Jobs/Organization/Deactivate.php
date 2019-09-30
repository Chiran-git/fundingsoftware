<?php

namespace App\Jobs\Organization;

use Auth;
use App\Organization;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Repositories\Contracts\OrganizationRepositoryInterface;

class Deactivate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization data
     *
     * @var array
     */
    public $organization;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Organization $organization)
    {
        $this->organization = $organization;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(OrganizationRepositoryInterface $repo)
    {
        return $repo->deactivate($this->organization->id, 
            [
                'deactivated_at' => date('Y-m-d H:i:s'),
                'deactivated_by' => Auth::user()->id
            ]
        );
    }
}
