<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HappyHoursPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'happy_hours.show',
                'display_name' => 'Show',
                'group_name' => 'Happy Hours',
                'guard_name' => 'web',
            ],
            [
                'name' => 'happy_hours.create',
                'display_name' => 'Create',
                'group_name' => 'Happy Hours',
                'guard_name' => 'web',
            ],
            [
                'name' => 'happy_hours.edit',
                'display_name' => 'Edit',
                'group_name' => 'Happy Hours',
                'guard_name' => 'web',
            ],
            [
                'name' => 'happy_hours.delete',
                'display_name' => 'Delete',
                'group_name' => 'Happy Hours',
                'guard_name' => 'web',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }
    }
}
