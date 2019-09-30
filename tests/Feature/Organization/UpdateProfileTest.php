<?php

namespace Tests\Feature\Organization;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UpdateProfileTest extends TestCase
{
    /**
     * Method to product normal payload for organization
     * with only profile fields
     *
     * @return array
     */
    private function payload()
    {
        return [
            'name' => $this->faker->company,
            'address1' => $this->faker->streetAddress,
            'city' => $this->faker->city,
            'state' => $this->faker->stateAbbr,
            'zipcode' => $this->faker->postcode,
            'phone' => $this->faker->phoneNumber,
            'currency' => config('app.defaults.currency'),
            'country' => config('app.defaults.country'),
            'slug' => $this->faker->slug,
        ];
    }

    /**
     * Test update profile API
     *
     * @group organization
     *
     * @return void
     */
    public function testUpdateOrganizationProfile()
    {
        // Create an organization
        $organization = $this->createOrganization();

        $url = route('api.organization.profile', ['organization' => $organization->id], false);

        // First test with only name field and it show through 422 error
        $payload = [
            'name' => $this->faker->company,
        ];

        $response = $this->actingAs($organization->owner, 'api')
            ->json('PUT', $url, $payload);

        $response->assertStatus(422);

        $payload = $this->payload();

        $response = $this->actingAs($organization->owner, 'api')
            ->json('PUT', $url, $payload);

        $response->assertOk();

        // Make sure that the organization with the given payload exists in the db
        $payload['id'] = $organization->id;
        unset($payload['currency']);
        unset($payload['country']);
        $this->assertDatabaseHas('organizations', $payload);
    }

    /**
     * Test updating someone else's org
     *
     * @group organization
     *
     * @return void
     */
    public function testUpdateOrganizationProfileUnauthorized()
    {
        // Create an organization
        $organization = $this->createOrganization();

        // Create another organization
        $otherOrganization = $this->createOrganization();

        // Owner of first org trying to update other org
        $url = route('api.organization.profile', ['organization' => $otherOrganization->id], false);

        $payload = $this->payload();

        $response = $this->actingAs($organization->owner, 'api')
            ->json('PUT', $url, $payload);

        $response->assertStatus(403);
    }
}
