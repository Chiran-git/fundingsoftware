<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\User' => 'App\Policies\UserPolicy',
        'App\Organization' => 'App\Policies\OrganizationPolicy',
        'App\Campaign' => 'App\Policies\CampaignPolicy',
        'App\OrganizationConnectedAccount' => 'App\Policies\OrganizationConnectedAccountPolicy',
        'App\Invitation' => 'App\Policies\InvitationPolicy',
        'App\Donor' => 'App\Policies\DonorPolicy'
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
        // Customize the passport cookie name
        Passport::cookie('rocketjar_token');
    }
}
