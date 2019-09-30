<?php

namespace App\Jobs\Admin\Organization;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\Admin\OrganizationWasCreated;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;

class Create implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Organization data (including owner data)
     *
     * @var array
     */
    public $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(
        OrganizationRepositoryInterface $organizationRepo,
        UserRepositoryInterface $userRepo,
        CountryRepositoryInterface $countryRepo,
        CurrencyRepositoryInterface $currencyRepo
    ) {

        // First create the User if not already exists
        $user = $userRepo->createIfNotExists(
            [
                'first_name' => $this->data['first_name'],
                'last_name' => $this->data['last_name'],
                'email' => $this->data['email'],
                'password' => Hash::make(str_random())
            ]
        );

        $organization = $organizationRepo->store(
            [
                'name' => $this->data['name'],
                'owner_id' => $user->id,
                'currency_id' => $currencyRepo->findDefault()->id,
                'country_id' => $countryRepo->findDefault()->id,
                'primary_color' => config('app.defaults.primary_color'),
                'secondary_color' => config('app.defaults.secondary_color'),
                'address1' => $this->data['address1'],
                'address2' => $this->data['address2'],
                'city' => $this->data['city'],
                'state' => $this->data['state'],
                'zipcode' => $this->data['zipcode'],
                'phone' => $this->data['phone'],
                'slug' => $this->data['slug'],
            ]
        );
dd($organization);
        // Attach the user with the organization
        $user->organizations()->attach($organization->id, ['role' => 'owner']);

        if ($user && $organization) {
            event(new OrganizationWasCreated($user, $organization));
        }

        return $organization;
    }
}
