<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Map of contracts and repos
     *
     * @var array
     */
    private $pairs = [
        \App\Repositories\Contracts\OrganizationRepositoryInterface::class => \App\Repositories\Eloquent\OrganizationRepository::class,
        \App\Repositories\Contracts\UserRepositoryInterface::class => \App\Repositories\Eloquent\UserRepository::class,
        \App\Repositories\Contracts\CountryRepositoryInterface::class => \App\Repositories\Eloquent\CountryRepository::class,
        \App\Repositories\Contracts\CurrencyRepositoryInterface::class => \App\Repositories\Eloquent\CurrencyRepository::class,
        \App\Repositories\Contracts\DonorQuestionRepositoryInterface::class => \App\Repositories\Eloquent\DonorQuestionRepository::class,
        \App\Repositories\Contracts\CampaignRewardRepositoryInterface::class => \App\Repositories\Eloquent\CampaignRewardRepository::class,
        \App\Repositories\Contracts\CampaignRepositoryInterface::class => \App\Repositories\Eloquent\CampaignRepository::class,
        \App\Repositories\Contracts\OrganizationUserRepositoryInterface::class => \App\Repositories\Eloquent\OrganizationUserRepository::class,
        \App\Repositories\Contracts\OrganizationConnectedAccountRepositoryInterface::class => \App\Repositories\Eloquent\OrganizationConnectedAccountRepository::class,
        \App\Repositories\Contracts\InvitationRepositoryInterface::class => \App\Repositories\Eloquent\InvitationRepository::class,
        \App\Repositories\Contracts\DonorRepositoryInterface::class => \App\Repositories\Eloquent\DonorRepository::class,
        \App\Repositories\Contracts\DonationRepositoryInterface::class => \App\Repositories\Eloquent\DonationRepository::class,
        \App\Repositories\Contracts\DonationQuestionAnswerRepositoryInterface::class => \App\Repositories\Eloquent\DonationQuestionAnswerRepository::class,
        \App\Repositories\Contracts\PayoutRepositoryInterface::class => \App\Repositories\Eloquent\PayoutRepository::class,

    ];

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        foreach ($this->pairs as $interface => $class) {
            $matches = [];
            // Get the model name based on the repository interface name
            // and then inject the model into the repository object
            preg_match('/([a-zA-Z]+)RepositoryInterface/', $interface, $matches);

            $model = sprintf('\App\%s', $matches[1]);

            $this->app->bind(
                $interface,
                function ($app) use ($model, $class) {
                    return new $class(new $model);
                }
            );
        }
    }
}
