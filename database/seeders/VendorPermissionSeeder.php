<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class VendorPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                'name' => 'vendor-list',
                'display_name' => 'list',
                'group_name' => 'Vendor Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'vendor-create',
                'display_name' => 'create',
                'group_name' => 'Vendor Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'vendor-edit',
                'display_name' => 'edit',
                'group_name' => 'Vendor Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'vendor-delete',
                'display_name' => 'delete',
                'group_name' => 'Vendor Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
