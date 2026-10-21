<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PosLoginPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                'name' => 'pos.login',
                'display_name' => 'POS Login',
                'group_name' => 'POS Login',
                'guard_name' => 'api',
                'permission_type' => 2
            ]
        ]);
    }
}
