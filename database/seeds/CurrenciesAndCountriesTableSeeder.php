<?php

use App\Currency;
use App\Country;
use Illuminate\Database\Seeder;

class CurrenciesAndCountriesTableSeeder extends Seeder
{
    /**
     * Currencies to seed
     *
     * @var array
     */
    private $currencies = [
        [
            'name' => 'United States Dollar',
            'iso_code' => 'USD',
            'symbol' => '$',
        ]
    ];

    /**
     * Countries to seed
     *
     * @var array
     */
    private $countries = [
        [
            'name' => 'United States',
            'iso_code' => 'US',
            'currency' => 'USD',
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedCurrencies();
        $this->seedCountries();
    }

    /**
     * Method to seed currencies
     *
     * @return void
     */
    private function seedCurrencies()
    {
        foreach ($this->currencies as $currency) {
            Currency::create($currency);
        }
    }

    /**
     * Method to seed countries
     *
     * @return void
     */
    private function seedCountries()
    {
        foreach ($this->countries as $country) {
            // Find the currency id for this country
            $currency = Currency::where('iso_code', $country['currency'])->first();

            // Default currency will be USD if not found
            if (! $currency) {
                $currency = Currency::where('iso_code', config('app.defaults.currency'))
                    ->first();
            }

            $country['currency_id'] = $currency->id;
            unset($country['currency']);

            Country::create($country);
        }
    }
}
