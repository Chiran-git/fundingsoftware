<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        Artisan::call('passport:install --no-interaction');
        $this->call(UsersTableSeeder::class);
        $this->call(CurrenciesAndCountriesTableSeeder::class);
        $this->call(CampaignCategoriesTableSeeder::class);
    }
}
