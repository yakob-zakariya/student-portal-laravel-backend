<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Enums\Permission as PermissionEnum;
use App\Enums\Role as RoleEnum;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\AcademicYear;


class AcademicYearTest extends TestCase
{
    use RefreshDatabase;
    protected $user;
    protected $authorized_user;
    protected $base_url;
    protected $academicYear;
    protected $academicYear2;



    public function setup(): void
    {
        parent::setUp();
        $this->seed();

        $this->user = User::factory()->create();
        $this->authorized_user = User::factory()->create();
        $this->authorized_user->assignRole(RoleEnum::SUPER_ADMIN);
        $this->base_url = config('app.api_prefix');
        $this->academicYear = AcademicYear::create(['name' => '2020/2021']);
        $this->academicYear2 = AcademicYear::create(['name' => '2021/2022']);
    }

    protected function url(string $endpoint): string
    {
        return $this->base_url . $endpoint;
    }

    // Authentication Tests

    public function test_unauthenticated_user_cannot_view_academic_year()
    {
        $response = $this->getJson($this->url('/academic-years'));
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_create_academic_year()
    {
        $response = $this->postJson($this->url('/academic-years'), [
            'name' => '2021/2022'
        ]);

        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_view_a_single_academic_year()
    {
        $response = $this->getJson($this->url('/academic-years/' . $this->academicYear->id));
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_update_academic_year()
    {
        $response = $this->putJson($this->url('/academic-years/' . $this->academicYear->id), [
            'name' => '2022/2023'
        ]);
        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_delete_academic_year()
    {
        $response = $this->deleteJson($this->url('/academic-years/' . $this->academicYear->id));
        $response->assertStatus(401);
    }

    // Authorization Tests

    public function test_authorized_user_can_view_academic_year()
    {
        $response = $this->actingAs($this->authorized_user)->getJson($this->url('/academic-years'));
        $response->assertStatus(200);
    }

    public function test_authorized_user_can_create_academic_year()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/academic-years'), [
            'name' => '2022/2023'
        ]);
        $response->assertStatus(201);
        $this->assertDatabaseHas('academic_years', ['name' => '2021/2022']);
    }

    public function test_authorized_user_can_view_a_single_academic_year()
    {
        $response = $this->actingAs($this->authorized_user)->getJson($this->url('/academic-years/' . $this->academicYear->id));
        $response->assertStatus(200);
    }

    public function test_authorized_user_can_update_academic_year()
    {
        $response = $this->actingAs($this->authorized_user)->putJson($this->url('/academic-years/' . $this->academicYear->id), [
            'name' => '2022/2023'
        ]);
        $response->assertStatus(200);
        $this->assertDatabaseHas('academic_years', ['name' => '2021/2022']);
    }

    public function test_authorized_user_can_delete_academic_year()
    {
        $response = $this->actingAs($this->authorized_user)->deleteJson($this->url('/academic-years/' . $this->academicYear->id));

        $response->assertStatus(204);

        $this->assertDatabaseMissing('academic_years', ['name' => '2020/2021']);
    }

    // Validation Tests

    public function test_creating_academic_year_requires_name()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/academic-years'), [
            'name' => ''
        ]);

        $response->assertStatus(422);
    }

    public function test_creating_academic_year_requires_unique_name()
    {
        $response = $this->actingAs($this->authorized_user)->postJson($this->url('/academic-years'), [
            'name' => $this->academicYear->name
        ]);

        $response->assertStatus(422);
    }

    public function test_updating_academic_year_requires_unique_name()
    {
        $response = $this->actingAs($this->authorized_user)->putJson($this->url('/academic-years/' . $this->academicYear->id), [
            'name' => $this->academicYear2->name
        ]);

        $response->assertStatus(422);
    }
}
