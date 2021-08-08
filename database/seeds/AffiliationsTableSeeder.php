<?php

use Carbon\Carbon;
use App\Affiliation;
use Illuminate\Database\Seeder;

class AffiliationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Affiliation::truncate();
        $timestamp = Carbon::now();

        Affiliation::create([
            'name' => 'Alumni',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        Affiliation::create([
            'name' => 'Friend',
            'created_at' => $timestamp,
            'updated_at' => $timestamp

        ]);

        Affiliation::create([
            'name' => 'Parent/Family',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        Affiliation::create([
            'name' => 'Faculty/Staff',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        Affiliation::create([
            'name' => 'Student',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);

        Affiliation::create([
            'name' => 'Other',
            'created_at' => $timestamp,
            'updated_at' => $timestamp
        ]);
    }
}
