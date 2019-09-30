<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Stripe\Stripe;

class StripeServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe::setApiVersion("2019-05-16");
        Stripe::setClientId(env('STRIPE_CLIENT_ID'));
    }
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
