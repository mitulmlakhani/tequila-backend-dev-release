<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class TipSettingPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            [
                'name' => 'tip-settings.show',
                'display_name' => 'View Tip Settings',
                'group_name' => 'Tip Settings',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                'name' => 'tip-settings.save',
                'display_name' => 'Save Tip Settings',
                'group_name' => 'Tip Settings',
                'guard_name' => 'web',
                'permission_type' => 2
            ],
            [
                "name" => "tips.distribute-shared-tips",
                "display_name" => "Distribute Shared Tips",
                "group_name" => "Tip Settings",
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
