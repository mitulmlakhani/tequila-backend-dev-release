<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ItemPriceEditorPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'price-editor.index',
                'display_name' => 'List',
                'group_name' => 'Item Price Editor',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'price-editor.update',
                'display_name' => 'Update',
                'group_name' => 'Item Price Editor',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'price-editor.bulk-update',
                'display_name' => 'Bulk Update',
                'group_name' => 'Item Price Editor',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
