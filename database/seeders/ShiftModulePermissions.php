<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ShiftModulePermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'shifts.index',
                'display_name' => 'List',
                'group_name' => 'Shifts',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'shifts.store',
                'display_name' => 'Create',
                'group_name' => 'Shifts',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'shifts.update',
                'display_name' => 'Update',
                'group_name' => 'Shifts',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'shifts.destroy',
                'display_name' => 'Delete',
                'group_name' => 'Shifts',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
