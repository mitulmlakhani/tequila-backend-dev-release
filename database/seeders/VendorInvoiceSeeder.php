<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class VendorInvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                'name' => 'vendor-invoice-read',
                'display_name' => 'list',
                'group_name' => 'Vendor Invoice',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'vendor-invoice-create',
                'display_name' => 'create',
                'group_name' => 'Vendor Invoice',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'vendor-invoice-edit',
                'display_name' => 'edit',
                'group_name' => 'Vendor Invoice',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'vendor-invoice-delete',
                'display_name' => 'delete',
                'group_name' => 'Vendor Invoice',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
