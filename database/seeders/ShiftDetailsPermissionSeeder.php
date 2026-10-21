<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ShiftDetailsPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                "name"  => "get.clocking-details-logs",
                "display_name"  => "Shift Details",
                "group_name"    => "Shift",
                "guard_name"    => "api",
                "permission_type"   => 2
            ]
        ]);
    }
}
