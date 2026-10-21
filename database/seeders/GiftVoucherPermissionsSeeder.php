<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class GiftVoucherPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'gift-vouchers.index',
                'display_name' => 'List',
                'group_name' => 'Gift Vouchers',
                'permission_type' => 2,
            ],
            [
                'name' => 'gift-vouchers.store',
                'display_name' => 'Create',
                'group_name' => 'Gift Vouchers',
                'permission_type' => 2,
            ],
            [
                'name' => 'gift-vouchers.update',
                'display_name' => 'Update',
                'group_name' => 'Gift Vouchers',
                'permission_type' => 2,
            ],
            [
                'name' => 'gift-vouchers.destroy',
                'display_name' => 'Delete',
                'group_name' => 'Gift Vouchers',
                'permission_type' => 2,
            ],
            [
                'name' => 'gift-voucher',
                'display_name' => 'Show',
                'group_name' => 'Gift Vouchers',
                'permission_type' => 2,
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                [
                    'display_name' => $permission['display_name'],
                    'group_name' => $permission['group_name'],
                    'permission_type' => $permission['permission_type'],
                ]
            );
        }
    }
}
