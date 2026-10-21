<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class ReservationPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reservationPermissions = [
            [
                'name' => 'reservations.list',
                'display_name' => 'View Reservations',
                'group_name' => 'Reservations',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'reservations.create',
                'display_name' => 'Create Reservations',
                'group_name' => 'Reservations',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'reservations.edit',
                'display_name' => 'Edit Reservations',
                'group_name' => 'Reservations',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'reservations.cancel',
                'display_name' => 'Cancel Reservations',
                'group_name' => 'Reservations',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'reservations.payments',
                'display_name' => 'Accept Payment',
                'group_name' => 'Reservations',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'reservations.settings',
                'display_name' => 'Settings',
                'group_name' => 'Reservations',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
        ];

        foreach ($reservationPermissions as $permission) {
            $permissionExists = Permission::where('name', $permission['name'])->exists();
            if (!$permissionExists) {
                Permission::create($permission);
            }
        }
    }
}
