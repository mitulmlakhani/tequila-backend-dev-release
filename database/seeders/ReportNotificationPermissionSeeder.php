<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportNotificationPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Permission::insert([
            [
                'name' => 'report-notification.index',
                'display_name' => 'View Report Notifications',
                'group_name' => 'Report Notifications Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'report-notification.store',
                'display_name' => 'Create Report Notification',
                'group_name' => 'Report Notifications Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'report-notification.destroy',
                'display_name' => 'Delete Report Notification',
                'group_name' => 'Report Notifications Management',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
        ]);
    }
}
