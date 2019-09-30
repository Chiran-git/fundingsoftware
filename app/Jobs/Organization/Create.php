<?php

namespace App\Jobs\Organization;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\Organization\OwnerWasCreated;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Contracts\CountryRepositoryInterface;
use App\Repositories\Contracts\CurrencyRepositoryInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;

use App\Events\Organization\WasCreated as OrganizationWasCreated;

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
                'password' => isset($this->data['password']) ? Hash::make($this->data['password']) : Hash::make(str_random()),
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
                'address1' => !empty($this->data['address1']) ? $this->data['address1'] : null,
                'address2' => !empty($this->data['address2']) ? $this->data['address2'] : null,
                'city' => !empty($this->data['city']) ? $this->data['city'] : null,
                'state' => !empty($this->data['state']) ? $this->data['state'] : null,
                'zipcode' => !empty($this->data['zipcode']) ? $this->data['zipcode'] : null,
                'phone' => !empty($this->data['phone']) ? $this->data['phone'] : null,
                'slug' => !empty($this->data['slug']) ? $this->data['slug'] : null,
            ]
        );

        // Attach the user with the organization
        $user->organizations()->attach($organization->id, ['role' => 'owner']);

        event(new OrganizationWasCreated($organization));

        if (! isset($this->data['password'])) {
            event(new OwnerWasCreated($user, $organization));
        }

        return $organization;
    }
}
