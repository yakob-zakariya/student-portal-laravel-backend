<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use App\Models\Department;

class CoordinatorUserTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $authoroized_user;
    protected $base_url;
    protected $department;


    public function setup(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create();
        $this->authoroized_user = User::factory()->create();
        $this->authoroized_user->assignRole(RoleEnum::SUPER_ADMIN);
        $this->base_url = config('app.api_prefix');

        $this->department = Department::create([
            'name' => 'computer science',
            'code' => 'cs'
        ]);
    }

    public function test_authorized_user_can_create_coordinator_user()
    {


        $response = $this->actingAs($this->authoroized_user)->postJson($this->base_url . '/users/coordinators', [
            'first_name' => 'Yakob',
            'middle_name' => 'demto',
            'last_name' => 'Mengesha',
            'email' => 'yakobmengesha@gmail.com',
            'role' => 'coordinator',
            'department_id' => $this->department->id,


        ]);


        // dd($response->json()['id']);


        $response->assertStatus(201);

        $this->assertDatabaseHas('users', ['email' => 'yakobmengesha@gmail.com']);

        $this->assertDatabaseHas('coordinators', ['user_id' => $response->json()['id'], 'department_id' => $this->department->id]);
    }
}
