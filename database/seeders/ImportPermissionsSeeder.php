<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class ImportPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'menu-import',
                'display_name' => 'Import menu',
                'group_name' => 'Menu Import/Export',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'menu-export',
                'display_name' => 'Export menu',
                'group_name' => 'Menu Import/Export',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
