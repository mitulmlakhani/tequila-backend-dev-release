<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;

class TicketHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tequilaUserPermissions = Permission::insert([
            [
                'name' => 'order-list',
                'display_name' => 'List',
                'group_name' => 'Ticket History',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'order',
                'display_name' => 'Ticket Detail',
                'group_name' => 'Ticket History',
                'guard_name' => 'web',
                'permission_type' => 2
            ]
        ]);
    }
}
