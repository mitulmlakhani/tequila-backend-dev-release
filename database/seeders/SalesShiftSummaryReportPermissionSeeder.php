<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SalesShiftSummaryReportPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                "name"  => "sales-summary-report",
                "display_name"  => "Report Sales-Summary-Report",
                "group_name"    => "Report",
                "guard_name"    => "api",
                "permission_type"   => 2
            ],
            [
                "name"  => "shift-summary-report",
                "display_name"  => "Report Shift-Summary-Report",
                "group_name"    => "Report",
                "guard_name"    => "api",
                "permission_type"   => 2
            ],
        ]);
    }
}
