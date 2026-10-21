<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class PaymentPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'manage-payment-methods',
                'display_name' => 'Payment Methods List',
                'group_name' => 'Payment Methods',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'manage-payment-method-edit',
                'display_name' => 'Enable Payment Methods',
                'group_name' => 'Payment Methods',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'payment-method-list',
                'display_name' => 'List',
                'group_name' => 'Payment Methods',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'payment-method-create',
                'display_name' => 'Create',
                'group_name' => 'Payment Methods',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'payment-method-edit',
                'display_name' => 'Edit',
                'group_name' => 'Payment Methods',
                'guard_name' => 'web',
                'permission_type' => 1
            ],
            [
                'name' => 'payment-method-delete',
                'display_name' => 'Delete',
                'group_name' => 'Payment Methods',
                'guard_name' => 'web',
                'permission_type' => 1
            ]
        ]);
    }
}
