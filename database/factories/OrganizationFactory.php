<?php

namespace Database\Factories;

use App\Organization;
use Illuminate\Database\Eloquent\Factories\Factory;


class OrganizationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \App\Organization::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

    $currencyRepo = app(\App\Repositories\Contracts\CurrencyRepositoryInterface::class);
    $countryRepo = app(\App\Repositories\Contracts\CountryRepositoryInterface::class);

        return [
            'name' => $faker->company,
            'owner_id' => function () {
                return \App\User::factory()->create()->id;
            },
            'address1' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'zipcode' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'slug' => $this->faker->slug,
            'currency_id' => $currencyRepo->findDefault()->id,
            'country_id' => $countryRepo->findDefault()->id,
            'primary_color' => config('app.defaults.primary_color'),
            'secondary_color' => config('app.defaults.secondary_color'),
        ];
    }
}
