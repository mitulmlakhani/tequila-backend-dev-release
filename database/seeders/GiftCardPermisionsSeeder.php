<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class GiftCardPermisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'gift-cards.index',
                'display_name' => 'List',
                'group_name' => 'Gift Card',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'gift-cards.update-balance',
                'display_name' => 'Balance Update',
                'group_name' => 'Gift Card',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'gift-cards.transactions',
                'display_name' => 'Transactions',
                'group_name' => 'Gift Card',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
