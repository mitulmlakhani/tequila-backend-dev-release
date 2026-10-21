<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class EmailAndEmployeeSalesSummaryReportPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                "name" => "employee-sales-report.all",
                "display_name" => "All Employee Sales Report",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reports.send-emails",
                "display_name" => "Send Report Emails",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ]
        ];


        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['name' => $permission['name'], 'guard_name' => $permission['guard_name']],
                $permission
            );
        }
    }
}
