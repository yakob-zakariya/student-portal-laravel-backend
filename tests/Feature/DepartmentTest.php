<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Department;
use App\Models\User;
use App\Enums\Role as RoleEnum;


class DepartmentTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $department;
    protected $authorized_user;
    protected $base_url;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed();
        $this->user = User::factory()->create();
        $this->authorized_user = User::factory()->create();
        $this->department = Department::factory()->create();
        $this->base_url = config('app.api_prefix') . '/departments';

        $this->authorized_user->assignRole(RoleEnum::SUPER_ADMIN);
    }

    protected function url(string $endpoint): string
    {
        return $this->base_url . $endpoint;
    }


    // Authentication Tests
    public function test_uanthenticated_user_cannot_view_department()
    {
        $response = $this->getJson($this->url('/'));
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_create_department()
    {
        $response = $this->postJson($this->url('/'), []);
        $response->assertStatus(401);
    }

    public function test_unautheticated_user_cannot_view_a_single_department()
    {
        $response = $this->getJson($this->url('/' . $this->department->id));
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_update_department()
    {
        $response = $this->putJson($this->url('/' . $this->department->id), []);
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_delete_department()
    {
        $response = $this->deleteJson($this->url('/' . $this->department->id));
        $response->assertStatus(401);
    }


    // Authorization Tests
    public function test_unauthorized_user_cannot_view_department()
    {
        $response = $this->actingAs($this->user)->getJson($this->url('/'));
        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_create_department()
    {
        $response = $this->actingAs($this->user)->postJson($this->url('/'), []);
        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_view_a_single_department()
    {
        $response = $this->actingAs($this->user)->getJson($this->url('/' . $this->department->id));
        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_update_department()
    {
        $response = $this->actingAs($this->user)->putJson($this->url('/' . $this->department->id), []);
        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_delete_department()
    {
        $response = $this->actingAs($this->user)->deleteJson($this->url('/' . $this->department->id));
        $response->assertStatus(403);
    }

    // Authorized User Tests
    public function test_authorized_user_can_view_departments()
    {
        $response = $this->actingAs($this->authorized_user)->getJson($this->url('/'));
        $response->assertStatus(200);
    }

    public function test_authorized_user_can_create_department()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/'), [
            'name' => 'Department 1',
            'code' => 'code'
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('departments', [
            'name' => 'Department 1',
            'code' => 'code'
        ]);
    }

    public function test_authorized_user_can_view_a_single_department()
    {
        $response = $this->actingAs($this->authorized_user)->getJson($this->url('/' . $this->department->id));
        $response->assertStatus(200);
    }

    public function test_authorized_user_can_update_department()
    {
        $response = $this->actingAs($this->authorized_user)->putJson($this->url('/' . $this->department->id), [
            'name' => 'Department 2',
            'code' => 'code2'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('departments', [
            'name' => 'Department 2',
            'code' => 'code2'
        ]);
    }

    public function test_authorized_user_can_delete_department()
    {
        $response = $this->actingAs($this->authorized_user)->deleteJson($this->url('/' . $this->department->id));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('departments', [
            'id' => $this->department->id
        ]);
    }


    // Validation Tests

    public function test_creating_department_requires_name_and_code()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/'), [
            'name' => '',
            'code' => ''
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name', 'code']);
    }

    public function test_creating_department_requires_unique_name()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/'), [
            'name' => $this->department->name,
            'code' => 'code1'
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['name']);
    }

    public function test_creating_department_requires_unique_code()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/'), [
            'name' => 'Department 1',
            'code' => $this->department->code
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['code']);
    }
}
