<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Enums\Role as RoleEnum;
use App\Enums\Permission as PermissionEnum;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $superAdminRole  =  Role::where('name', RoleEnum::SUPER_ADMIN)->first();
        foreach (PermissionEnum::cases() as $permission) {
            $perm = Permission::firstOrCreate(['name' => $permission]);
            $superAdminRole->givePermissionTo($perm);
        }
    }
}
