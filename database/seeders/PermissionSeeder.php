<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'cash_transactions.index',
                'display_name' => 'Cash In/out',
                'group_name' => 'Payment History',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'reservation.index',
                'display_name' => 'Reservations',
                'group_name' => 'Reservations',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'restaurant.storeAddressSettings',
                'display_name' => 'Address update',
                'group_name' => 'Settings',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'restaurant.addressSettings',
                'display_name' => 'Address view',
                'group_name' => 'Settings',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
