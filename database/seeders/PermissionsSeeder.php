<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'restaurant-list',
                'display_name' => 'list',
                'group_name' => 'Restaurant Management',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'restaurant-create',
                'display_name' => 'create',
                'group_name' => 'Restaurant Management',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'restaurant-edit',
                'display_name' => 'edit',
                'group_name' => 'Restaurant Management',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'restaurant-delete',
                'display_name' => 'delete',
                'group_name' => 'Restaurant Management',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'admin-role-list',
                'display_name' => 'list',
                'group_name' => 'Admin Role Management',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'admin-role-create',
                'display_name' => 'create',
                'group_name' => 'Admin Role Management',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'admin-role-edit',
                'display_name' => 'edit',
                'group_name' => 'Admin Role Management',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'admin-role-delete',
                'display_name' => 'delete',
                'group_name' => 'Admin Role Management',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
        ]);
       
        $permissions = Permission::insert([
            [
                'name' => 'role-list',
                'display_name' => 'Role list',
                'group_name' => 'Role Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'role-create',
                'display_name' => 'Role create',
                'group_name' => 'Role Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'role-edit',
                'display_name' => 'Role edit',
                'group_name' => 'Role Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'role-delete',
                'display_name' => 'Role delete',
                'group_name' => 'Role Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'user-list',
                'display_name' => 'User list',
                'group_name' => 'User Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'user-create',
                'display_name' => 'User create',
                'group_name' => 'User Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'user-edit',
                'display_name' => 'User edit',
                'group_name' => 'User Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'user-delete',
                'display_name' => 'User delete',
                'group_name' => 'User Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'category-list',
                'display_name' => 'Category list',
                'group_name' => 'Category Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'category-create',
                'display_name' => 'Category create',
                'group_name' => 'Category Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'category-edit',
                'display_name' => 'Category edit',
                'group_name' => 'Category Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'category-delete',
                'display_name' => 'Category delete',
                'group_name' => 'Category Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modifier-list',
                'display_name' => 'Modifier list',
                'group_name' => 'Modifier Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modifier-create',
                'display_name' => 'Modifier create',
                'group_name' => 'Modifier Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modifier-edit',
                'display_name' => 'Modifier edit',
                'group_name' => 'Modifier Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modifier-delete',
                'display_name' => 'Modifier delete',
                'group_name' => 'Modifier Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'ingredient-list',
                'display_name' => 'Ingredient list',
                'group_name' => 'Ingredient Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'ingredient-create',
                'display_name' => 'Ingredient create',
                'group_name' => 'Ingredient Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'ingredient-edit',
                'display_name' => 'Ingredient edit',
                'group_name' => 'Ingredient Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'ingredient-delete',
                'display_name' => 'Ingredient delete',
                'group_name' => 'Ingredient Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'item-list',
                'display_name' => 'Item list',
                'group_name' => 'Item Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'item-create',
                'display_name' => 'Item create',
                'group_name' => 'Item Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'item-edit',
                'display_name' => 'Item edit',
                'group_name' => 'Item Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'item-delete',
                'display_name' => 'Item delete',
                'group_name' => 'Item Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'floor-list',
                'display_name' => 'Floor list',
                'group_name' => 'Floor Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'floor-create',
                'display_name' => 'Floor create',
                'group_name' => 'Floor Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'floor-edit',
                'display_name' => 'Floor edit',
                'group_name' => 'Floor Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'floor-delete',
                'display_name' => 'Floor delete',
                'group_name' => 'Floor Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'table-list',
                'display_name' => 'Table list',
                'group_name' => 'Table Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'table-create',
                'display_name' => 'Table create',
                'group_name' => 'Table Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'table-edit',
                'display_name' => 'Table edit',
                'group_name' => 'Table Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'table-delete',
                'display_name' => 'Table delete',
                'group_name' => 'Table Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'variant-list',
                'display_name' => 'Variant list',
                'group_name' => 'Variant Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'variant-create',
                'display_name' => 'Variant create',
                'group_name' => 'Variant Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'variant-edit',
                'display_name' => 'Variant edit',
                'group_name' => 'Variant Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'variant-delete',
                'display_name' => 'Variant delete',
                'group_name' => 'Variant Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modifier-category-list',
                'display_name' => 'Modifier category list',
                'group_name' => 'Modifier Category Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modifier-category-create',
                'display_name' => 'Modifier category create',
                'group_name' => 'Modifier Category Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modifier-category-edit',
                'display_name' => 'Modifier category edit',
                'group_name' => 'Modifier Category Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'modifier-category-delete',
                'display_name' => 'Modifier category delete',
                'group_name' => 'Modifier Category Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'expense-list',
                'display_name' => 'Expense list',
                'group_name' => 'Expense Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'expense-create',
                'display_name' => 'Expense create',
                'group_name' => 'Expense Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'expense-edit',
                'display_name' => 'Expense edit',
                'group_name' => 'Expense Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'expense-delete',
                'display_name' => 'Expense delete',
                'group_name' => 'Expense Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'expense-type-list',
                'display_name' => 'Expense Type list',
                'group_name' => 'Expense Type Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'expense-type-create',
                'display_name' => 'Expense Type create',
                'group_name' => 'Expense Type Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'expense-type-edit',
                'display_name' => 'Expense Type edit',
                'group_name' => 'Expense Type Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'expense-type-delete',
                'display_name' => 'Expense Type delete',
                'group_name' => 'Expense Type Management',
                'guard_name' => 'web',
            ],

            [
                'name' => 'tax-class-list',
                'display_name' => 'Tax Class list',
                'group_name' => 'Tax Class Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tax-class-create',
                'display_name' => 'Tax Class create',
                'group_name' => 'Tax Class Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tax-class-edit',
                'display_name' => 'Tax Class edit',
                'group_name' => 'Tax Class Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tax-class-delete',
                'display_name' => 'Tax Class delete',
                'group_name' => 'Tax Class Management',
                'guard_name' => 'web',
            ],

            [
                'name' => 'tax-list',
                'display_name' => 'Tax list',
                'group_name' => 'Tax Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tax-create',
                'display_name' => 'Tax create',
                'group_name' => 'Tax Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tax-edit',
                'display_name' => 'Tax edit',
                'group_name' => 'Tax Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tax-delete',
                'display_name' => 'Tax delete',
                'group_name' => 'Tax Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tip-list',
                'display_name' => 'Tip list',
                'group_name' => 'Tip Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tip-create',
                'display_name' => 'Tip create',
                'group_name' => 'Tip Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tip-edit',
                'display_name' => 'Tip edit',
                'group_name' => 'Tip Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'tip-delete',
                'display_name' => 'Tip delete',
                'group_name' => 'Tip Management',
                'guard_name' => 'web',
            ],

            [
                'name' => 'reservation-list',
                'display_name' => 'Reservation list',
                'group_name' => 'Reservation Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'reservation-create',
                'display_name' => 'Reservation create',
                'group_name' => 'Reservation Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'reservation-edit',
                'display_name' => 'Reservation edit',
                'group_name' => 'Reservation Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'reservation-delete',
                'display_name' => 'Reservation delete',
                'group_name' => 'Reservation Management',
                'guard_name' => 'web',
            ],
        ]);
    }
}
