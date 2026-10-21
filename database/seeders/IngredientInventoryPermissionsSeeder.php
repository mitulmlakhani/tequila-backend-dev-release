<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class IngredientInventoryPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'ingredient-inventory-list',
                'display_name' => 'List',
                'group_name' => 'Ingredient Inventory',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'ingredient-inventory-create',
                'display_name' => 'Create',
                'group_name' => 'Ingredient Inventory',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'ingredient-inventory-delete',
                'display_name' => 'Delete',
                'group_name' => 'Ingredient Inventory',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'ingredient-inventory-transactions',
                'display_name' => 'Inventory Transactions',
                'group_name' => 'Ingredient Inventory',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
