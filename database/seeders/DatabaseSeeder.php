<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'first_name' => 'Yakob',
            "middle_name" => "Zakariya",
            "last_name" => "Aman",
            'email' => 'yysiyzx07@gmail.com',
            'password' => bcrypt('password'),
            'username' => 'ADM/1000/12'
        ]);

        $this->call([
            RoleSeeder::class,
            // CourseSeeder::class,
            PermissionSeeder::class,
        ]);

        $super_admin_role = \Spatie\Permission\Models\Role::where('name', 'super-admin')->first();
        $user->assignRole($super_admin_role);
    }
}
