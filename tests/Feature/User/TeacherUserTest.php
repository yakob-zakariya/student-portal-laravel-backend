<?php

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;

class TeacherUserTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $authorized_user;
    protected $base_url;

    public function setup(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create();
        $this->authorized_user = User::factory()->create();

        $this->authorized_user->assignRole(RoleEnum::SUPER_ADMIN);
        $this->base_url = config('app.api_prefix');
    }


    // Authentication Tests
    public function test_unauthenticated_user_cannot_view_teachers()
    {
        $response = $this->getJson($this->base_url . '/users/teachers');
        $response->assertStatus(401);
    }
    public function test_unauthenticated_user_cannot_create_teacher_user()
    {
        $response = $this->postJson($this->base_url . '/users/teachers', [
            'first_name' => 'Yakob',
            'middle_name' => 'demto',
            'last_name' => 'Mengesha',
            'email' => 'yakob@gmail.com',
            'role' => 'teacher'
        ]);

        $response->assertStatus(401);
    }
    public function test_unauthenticated_user_cannot_update_teacher_user()
    {
        $response = $this->putJson($this->base_url . '/users/teachers/1', [
            'first_name' => 'Yakob',
            'middle_name' => 'demto',
            'last_name' => 'Mengesha',
            'email' => 'yakob@gmail.com',
            'role' => 'teacher'
        ]);

        $response->assertStatus(401);
    }
    public function test_unauthenticated_user_cannot_delete_teacher_user()
    {
        $response = $this->deleteJson($this->base_url . '/users/teachers/1');
        $response->assertStatus(401);
    }

    // Authorization Tests
    public function test_unauthorized_user_cannot_view_teachers()
    {
        $response = $this->actingAs($this->user)->getJson($this->base_url . '/users/teachers');
        $response->assertStatus(403);
    }



    public function test_authorized_user_can_create_teacher_user()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->base_url . '/users/teachers', [
            'first_name' => 'Yakob',
            'middle_name' => 'demto',
            'last_name' => 'Mengesha',
            'email' => 'yakobmangesh@gmail.com',
            'role' => 'teacher',

        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', ['email' => 'yakobmangesh@gmail.com']);

        $this->assertDatabaseHas('teachers', ['user_id' => $response->json()['id']]);
    }
}
