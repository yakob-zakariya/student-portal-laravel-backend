<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionTest extends TestCase
{
    use RefreshDatabase;
    protected $baseUrl;
    protected $user;
    protected $authorized_user;
    protected $role;
    protected $permission;

    public function setup(): void
    {
        parent::setUp();
        $this->seed();
        $this->baseUrl = config('app.api_prefix');
        $this->user = User::factory()->create();
        $this->authorized_user = User::factory()->create();
        $this->authorized_user->assignRole(RoleEnum::SUPER_ADMIN);
        $this->role = Role::create(['name' => 'test role']);
        $this->permission = Permission::create(['name' => 'test permission']);
    }

    public function url(string $endpoint): string
    {
        return $this->baseUrl . $endpoint;
    }

    // Authentication Tests
    public function test_unauthenticated_user_cannot_view_permissions()
    {
        $response = $this->getJson($this->url('/permissions'));
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_assign_permission_to_a_role()
    {
        $response = $this->postJson(
            $this->url("/roles/{$this->role->id}/assign-permissions"),
            [
                "permissions" => [$this->permission->id]
            ]
        );

        $response->assertStatus(401);
    }



    public function test_unauthenticated_user_cannot_revoke_permissions_from_a_role()
    {
        $response = $this->postJson($this->url("/roles/{$this->role->id}/revoke-permissions"), [
            'permissions' => [$this->permission->id]
        ]);

        $response->assertStatus(401);
    }

    // Validation Tests

    public function test_assigning_permissions_to_a_role_requires_permissions()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url("/roles/{$this->role->id}/assign-permissions"), ["permissions" => [999]]);

        $response->assertStatus(422);
    }

    public function test_revoking_permissions_from_a_role_requires_permissions()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url("/roles/{$this->role->id}/revoke-permissions"), ["permissions" => [999]]);

        $response->assertStatus(422);
    }

    // Authorization Tests

    public function test_unauthorized_user_cannot_view_permissions()
    {
        $response = $this->actingAs($this->user)->getJson($this->url("/permissions"));
        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_assign_permission_to_a_role()
    {
        $response = $this->actingAs($this->user)->postJson(
            $this->url("/roles/{$this->role->id}/assign-permissions"),
            [
                "permissions" => [$this->permission->id]
            ]
        );

        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_revoke_permissions_from_a_role()
    {
        $response = $this->actingAs($this->user)->postJson($this->url("/roles/{$this->role->id}/revoke-permissions"), [
            'permissions' => [$this->permission->id]
        ]);

        $response->assertStatus(403);
    }



    public function test_authorized_user_can_view_permissions()
    {
        $response = $this->actingAs($this->authorized_user)->getJson($this->url('/permissions'));
        $response->assertStatus(200);
    }

    public function test_authorized_user_can_assing_permissions_to_a_role()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url("/roles/{$this->role->id}/assign-permissions"), [
            'permissions' => [$this->permission->id]
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('role_has_permissions', [
            "role_id" => $this->role->id,
            "permission_id" => $this->permission->id
        ]);
    }

    public function test_authorized_user_can_revoke_permissions_from_a_role()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url("/roles/{$this->role->id}/revoke-permissions"), [
            'permissions' => [$this->permission->id]
        ]);

        $response->assertStatus(204);

        $this->assertDatabaseMissing('role_has_permissions', [
            'role_id' => $this->role->id,
            'permission_id' => $this->permission->id
        ]);
    }
}
