<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ItemTypePermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'item_tags',
                'display_name' => 'List',
                'group_name' => 'Item Types',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'item_tags-create',
                'display_name' => 'Create',
                'group_name' => 'Item Types',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'item_tag-edit',
                'display_name' => 'Edit',
                'group_name' => 'Item Types',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'item_tags.destroy',
                'display_name' => 'Delete',
                'group_name' => 'Item Types',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
