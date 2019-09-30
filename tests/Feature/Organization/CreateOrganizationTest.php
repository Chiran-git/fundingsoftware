<?php

namespace Tests\Feature\Organization;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateOrganizationTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @group organization
     *
     * @return void
     */
    public function testSignup()
    {
        $url = route('api.organization.store', null, false);

        // Post with all fields empty
        $response = $this->json('POST', $url);

        $response->assertStatus(422);

        $password = 'Ranium123$';

        $payload = [
            'name' => $this->faker->company,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'password' => $password,
            'password_confirmation' => $password,
        ];

        $response = $this->json('POST', $url, $payload);

        $response->assertOk();

        // Creating with same payload should give validation error
        $response = $this->json('POST', $url, $payload);

        $response->assertStatus(422);
    }
}
