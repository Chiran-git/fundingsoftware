<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Organization;
use Faker\Generator as Faker;

$factory->define(Organization::class, function (Faker $faker) {

    $currencyRepo = app(\App\Repositories\Contracts\CurrencyRepositoryInterface::class);
    $countryRepo = app(\App\Repositories\Contracts\CountryRepositoryInterface::class);

    return [
        'name' => $faker->company,
        'owner_id' => function () {
            return factory(\App\User::class)->create()->id;
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
});
