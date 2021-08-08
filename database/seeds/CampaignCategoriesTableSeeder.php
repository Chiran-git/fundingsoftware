<?php

use Carbon\Carbon;
use App\CampaignCategory;
use Illuminate\Database\Seeder;

class CampaignCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        CampaignCategory::truncate();
        $timestamp = Carbon::now();

        CampaignCategory::create([
            'name' => 'General Fund',
            'created_at' => $timestamp,
            'updated_at' => $timestamp

        ]);

        CampaignCategory::create([
            'name' => 'Athletics',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        CampaignCategory::create([
            'name' => 'Alumni',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        CampaignCategory::create([
            'name' => 'University',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        CampaignCategory::create([
            'name' => 'Other',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);
    }
}
