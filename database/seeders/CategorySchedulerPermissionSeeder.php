<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class CategorySchedulerPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                'name' => 'category-schedule-list',
                'display_name' => 'Category Schedule list',
                'group_name' => 'Category Schedule Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'category-schedule-create',
                'display_name' => 'Category Schedule create',
                'group_name' => 'Category Schedule Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'category-schedule-edit',
                'display_name' => 'Category Schedule edit',
                'group_name' => 'Category Schedule Management',
                'guard_name' => 'web',
            ],
            [
                'name' => 'category-schedule-delete',
                'display_name' => 'Category Schedule delete',
                'group_name' => 'Category Schedule Management',
                'guard_name' => 'web',
            ],
        ]);
    }
}
