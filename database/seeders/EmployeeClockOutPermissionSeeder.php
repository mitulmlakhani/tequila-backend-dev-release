<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class EmployeeClockOutPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert(
            [
                [
                    'name' => 'employee-clockout',
                    'display_name' => 'Employee Clock Out',
                    'group_name' => 'User Management',
                    'guard_name' => 'api',
                    'permission_type' => 2
                ]
            ]
        );
    }
}
