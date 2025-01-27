<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'academicYear-list',
            'academicYear-show',

            'academicYear-create',
            'academicYear-edit',
            'academicYear-delete',


            'course-list',
            'course-show',

            'course-create',
            'course-edit',
            'course-delete',

        ];



        foreach ($permissions as $permission) {
            Permission::createOrFirst(['name' => $permission]);
        }
    }
}
