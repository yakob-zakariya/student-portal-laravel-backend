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
            'password' => bcrypt('password'),
        ]);


        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);



        // $response->dumpHeaders();
        // $response->dump();

        $response->assertStatus(200);

        // $response->assertJsonStructure([
        //     'token',
        //     'user',
        // ]);
    }

    public function test_a_user_cannot_login_with_invalid_credentials()
    {


        $user = User::factory()->create([
            'password' => bcrypt('password'),
        ]);


        // Make a POST request with invalid credentials
        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'wrongpassword',
        ]);

        // Assert the response status is 401 (Unauthorized)
        $response->assertStatus(422);

        // Assert the response contains an error message
        // $response->assertJson([
        //     'message' => 'The provided credentials are incorrect.',
        //     'errors' => [
        //         'email' => [
        //             'The provided credentials are incorrect.'
        //         ]
        //     ]
        // ]);
    }
}
