<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        '\App\Events\Organization\WasCreated' => [],
        'App\Events\Organization\InvitationWasCreated' => [
            'App\Listeners\Organization\SendInvitationCreatedNotification',
        ],
        'App\Events\Donation\DonationWasMade' => [
            'App\Listeners\Donation\UpdateAggregateFields',
            'App\Listeners\Donation\SendDonorNotification',
            'App\Listeners\Donation\SendAdminNotification',
        ],
        '\App\Events\Organization\ConnectedAccountWasDeleted' => [],
        '\App\Events\Campaign\CampaignWasCreated' => [],
        '\App\Events\Campaign\CampaignWasUpdated' => [],
        'App\Events\Donation\RewardWasGiven' => [
            'App\Listeners\Donation\IncrementQuantityRewarded',
        ],
        'App\Events\Admin\AdminWasCreated' => [
            'App\Listeners\Admin\SendAdminAlert'
        ],
        'App\Events\Organization\OwnerWasCreated' => [
            'App\Listeners\Admin\SendPasswordResetNotification'
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
