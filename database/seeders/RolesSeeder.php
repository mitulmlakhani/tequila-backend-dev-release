<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = Role::insert([
            [
                'name' => 'RestaurantSuperAdmin',
                'guard_name' => 'web',
                'is_deletable' => false,
                'status' => true,
                'role_type'=>2
            ]
            // [
            //     'name' => 'Admin',
            //     'guard_name' => 'web',
            //     'is_deletable' => 'N',
            //     'status' => 'Active'
            // ],
            // [
            //     'name' => 'Manager',
            //     'guard_name' => 'web',
            //     'is_deletable' => 'N',
            //     'status' => 'Active'
            // ],
            // [
            //     'name' => 'Cashier',
            //     'guard_name' => 'api',
            //     'is_deletable' => 'N',
            //     'status' => 'Active'
            // ],
            // [
            //     'name' => 'Chef',
            //     'guard_name' => 'api',
            //     'is_deletable' => 'N',
            //     'status' => 'Active'
            //     ],
            // [
            //     'name' => 'Waiter',
            //     'guard_name' => 'api',
            //     'is_deletable' => 'N',
            //     'status' => 'Active'
            // ]
        ]);
    }
}
