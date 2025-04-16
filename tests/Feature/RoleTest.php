<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Enums\Role as RoleEnum;
use App\Enums\Permission as PermissionEnum;
use Spatie\Permission\Models\Role;


class RoleTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $authorized_user;
    protected $role;
    protected $baseUrl;

    public function setup(): void
    {
        parent::setUp();
        $this->seed();
        $this->baseUrl = config('app.api_prefix');

        $this->user = User::factory()->create([
            'username' => 'UGR/0000/00',
        ]);
        $this->authorized_user = User::factory()->create([
            'username' => 'UGR/0000/11'
        ]);

        $this->authorized_user->assignRole(RoleEnum::SUPER_ADMIN);

        $this->role = Role::create(['name' => 'test role']);
    }

    protected function url(string $endpoint): string
    {
        return $this->baseUrl . $endpoint;
    }

    // Authentication test
    public function test_unauthenticated_user_cannot_veiw_roles()
    {

        $response = $this->getJson($this->url('/roles'));
        $response->assertStatus(401);
    }

    // Authorization Tests

    public function test_unauthorized_user_cannot_view_roles()
    {
        $response = $this->actingAs($this->user)->getJson($this->url('/roles'));
        $response->assertStatus(403);
    }
    public function test_unauthorized_user_cannot_create_roles()
    {
        $response = $this->actingAs($this->user)->postJson($this->url('/roles'), []);
        $response->assertStatus(403);
    }
    public function test_unauthorized_user_cannot_view_role()
    {
        $response = $this->actingAs($this->user)->getJson($this->url('/roles/' . $this->role->id));
        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_update_role()
    {
        $response = $this->actingAs($this->user)->putJson($this->url('/roles/' . $this->role->id), []);
        $response->assertStatus(403);
    }
    public function test_unauthorized_user_cannot_delete_role()
    {
        $response = $this->actingAs($this->user)->deleteJson($this->url('/roles/' . $this->role->id));
        $response->assertStatus(403);
    }

    // Validation tests
    public function test_creating_role_requires_name()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/roles'), [
            'name' => ''
        ]);
        $response->assertStatus(422);
    }

    public function test_creating_role_require_unique_name()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/roles'), [
            'name' => $this->role->name
        ]);
        $response->assertStatus(422);
    }

    // CRUD Tests
    public function test_authroized_user_can_view_roles()
    {

        $response = $this->actingAs($this->authorized_user)->getJson($this->url('/roles'));
        $response->assertStatus(200);
    }

    public function test_authroized_user_can_create_role()
    {

        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/roles'), [
            'name' => 'admin22'
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('roles', [
            'name' => 'admin22'
        ]);
    }

    public function test_authorized_user_can_view_role()
    {


        $response = $this->actingAs($this->authorized_user)->getJson($this->url('/roles/' . $this->role->id));
        $response->assertStatus(200);
    }

    public function test_authorized_user_can_update_role()
    {


        $response = $this->actingAs($this->authorized_user)->putJson($this->url('/roles/' . $this->role->id), [
            'name' => 'update role'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('roles', [
            'name' => 'update role'
        ]);
    }

    public function test_authorized_user_can_delete_role()
    {
        $response = $this->actingAs($this->authorized_user)->deleteJson($this->url("/roles/{$this->role->id}"));
        $response->assertStatus(204);
        $this->assertModelMissing($this->role);
    }
}
