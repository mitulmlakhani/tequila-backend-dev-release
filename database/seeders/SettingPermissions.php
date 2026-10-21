<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class SettingPermissions extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'settings.index',
                'display_name' => 'List',
                'group_name' => 'Settings',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'settings.update',
                'display_name' => 'Update',
                'group_name' => 'Settings',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}

