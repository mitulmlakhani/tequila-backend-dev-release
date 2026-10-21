<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class TerminalPermisionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $terminalPermissions = [
            [
                'name' => 'restaurant.terminal-settings.index',
                'display_name' => 'View Terminal Settings',
                'group_name' => 'Terminal Settings',
            ],
            [
                'name' => 'restaurant.terminal-settings.show',
                'display_name' => 'Show Terminal Details',
                'group_name' => 'Terminal Settings',
            ],
            [
                'name' => 'restaurant.terminal-settings.store',
                'display_name' => 'Create/Update Terminal',
                'group_name' => 'Terminal Settings',
            ],
            [
                'name' => 'restaurant.terminal-settings.destroy',
                'display_name' => 'Delete Terminal',
                'group_name' => 'Terminal Settings',
            ],
        ];

        foreach ($terminalPermissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission['name'], 'guard_name' => 'web'],
                [
                    'display_name' => $permission['display_name'],
                    'group_name' => $permission['group_name'],
                    'permission_type' => 2,
                ]
            );
        }
    }
}
