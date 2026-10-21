<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class DevicePermissionSeeder extends Seeder
{
    public function run(): void
    {
        Permission::insert([
            [
                'name' => 'pos-devices.index',
                'display_name' => 'View Devices',
                'group_name' => 'Device Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'pos-devices.store',
                'display_name' => 'Create Device',
                'group_name' => 'Device Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'pos-devices.show',
                'display_name' => 'View Device Details',
                'group_name' => 'Device Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'pos-devices.update',
                'display_name' => 'Edit Device',
                'group_name' => 'Device Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'pos-devices.destroy',
                'display_name' => 'Delete Device',
                'group_name' => 'Device Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
        ]);
    }
}
