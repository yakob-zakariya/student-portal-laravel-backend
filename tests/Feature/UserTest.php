<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Department;
use App\Models\Coordinator;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_protected_user_endpoint_cannot_be_accessed_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/user');

        $response->assertStatus(401);
    }


    public function test_a_protected_user_endpoint_returns_user_data_with_valid_token()
    {
        $user = User::factory()->create([
            'email' => 'yakob@gmail.com',
            'password' => bcrypt('password'),
            'username' => 'UGR/0000/12'
        ]);
        $token = $user->createToken('auth_token')->plainTextToken;



        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/user');


        $response->assertStatus(200);

        // dd($response->json());

        // $response->assertJsonStructure([


        //     "id",
        //     "first_name",
        //     "middle_name",
        //     "last_name",
        //     "email",
        //     "username",
        //     "role"



        // ]);
    }

    public function test_a_protected_user_endpoint_returns_user_data_with_role_and_department()
    {
        $user = User::factory()->create([
            'email' => 'yakob@gmail.com',
            'password' => bcrypt('password'),
            'username' => 'UGR/0000/12'
        ]);

        $role  = Role::create(['name' => 'coordinator', 'guard_name' => 'sanctum']);

        $user->assignRole($role);
        $department = Department::create([
            'name' => 'Computer Science',
            'code' => 'CS'
        ]);
        $user->coordinator()->create([
            'user_id' => $user->id,
            'department_id' => $department->id
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/user');


        $response->assertStatus(200);

        $response->assertJsonStructure([

            "id",
            "first_name",
            "middle_name",
            "last_name",
            "email",
            "username",
            "role",
            "department"

        ]);
    }
}
