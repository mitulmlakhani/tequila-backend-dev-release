<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ItemInventoryPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'inventory-list',
                'display_name' => 'List',
                'group_name' => 'Item Inventory',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'inventory-create',
                'display_name' => 'Create',
                'group_name' => 'Item Inventory',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'inventory-delete',
                'display_name' => 'Delete',
                'group_name' => 'Item Inventory',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'inventory-transactions',
                'display_name' => 'Transactions',
                'group_name' => 'Item Inventory',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
