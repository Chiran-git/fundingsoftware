<?php

namespace Tests;

use Faker\Generator as Faker;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * API's baset path
     *
     * @var string
     */
    public $apiBasePath = '/api/v1';

    /**
     * Property to hold Faker object
     *
     * @var \Faker\Generator
     */
    public $faker;

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Setup the test case
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Seed the database
        \Artisan::call('db:seed');

        $this->faker = app(Faker::class);
    }

    /**
     * Method to create a new organization and attach its owner
     *
     * @return void
     */
    public function createOrganization()
    {
        $organization = factory(\App\Organization::class)->create();

        // Attach organization owner with the organization
        $organization->owner->organizations()->attach($organization->id, ['role' => 'owner']);

        return $organization;
    }
}
