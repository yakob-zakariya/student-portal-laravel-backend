<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'yakob@gmail.com',
            'password' => bcrypt('password'),
            'username' => 'UGR/0000/12'
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => 'yakob@gmail.com',
            'password' => 'password'
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'token',
            'user',
        ]);
    }

    public function test_a_user_cannot_login_with_invalid_credentials()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'username' => 'UGR/0000/00'
        ]);

        // Make a POST request with invalid credentials
        $response = $this->postJson('/api/v1/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert the response status is 401 (Unauthorized)
        $response->assertStatus(401);

        // Assert the response contains an error message
        $response->assertJson([
            'message' => 'The provided credentials are incorrect.',
            'errors' => [
                'email' => [
                    'The provided credentials are incorrect.'
                ]
            ]
        ]);
    }
}
