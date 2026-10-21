<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class NewApiPermissionsSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            [
                "name" => "floors.view",
                "display_name" => "View Floor Layout",
                "group_name" => "Floor Layout",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "floors.manage",
                "display_name" => "Manage Floor Layout",
                "group_name" => "Floor Layout",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "menu.view",
                "display_name" => "View Menu",
                "group_name" => "Menu",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "menu.manage",
                "display_name" => "Manage Menu",
                "group_name" => "Menu",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "orders.view",
                "display_name" => "View Orders",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.process",
                "display_name" => "Process Orders",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.modify",
                "display_name" => "Modify Tickets",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.cancel",
                "display_name" => "Cancel Orders",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.split_combine_tickets",
                "display_name" => "Split/Combine Tickets",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.transfer",
                "display_name" => "Transfer Orders",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.service_charges",
                "display_name" => "Service Charges",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.remove_sales_tax",
                "display_name" => "Remove Sales Tax",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.apply_discount",
                "display_name" => "Apply Discount",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.open",
                "display_name" => "Open Orders",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.change_table",
                "display_name" => "Change Table",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.hold_items",
                "display_name" => "Hold Items",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "payments.view",
                "display_name" => "View Payments",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "payments.process",
                "display_name" => "Process Payments",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "payments.accept_cash_payments",
                "display_name" => "Accept Cash Payments",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "payments.refund",
                "display_name" => "Refund Payments",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "batch_deposits.process",
                "display_name" => "Process Batch Deposits",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "giftcard.view",
                "display_name" => "View Giftcard",
                "group_name" => "Giftcard",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "giftcard.process",
                "display_name" => "Process Giftcard",
                "group_name" => "Giftcard",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "tip.distribute",
                "display_name" => "Distribute Tips",
                "group_name" => "Tips",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "tip.view",
                "display_name" => "View Tips",
                "group_name" => "Tips",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "terminal_settings.view",
                "display_name" => "View Terminal Settings",
                "group_name" => "Terminal Settings",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "terminal_settings.manage",
                "display_name" => "Manage Terminal Settings",
                "group_name" => "Terminal Settings",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "kds.view",
                "display_name" => "View KDS Settings",
                "group_name" => "KDS Settings",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "kds.manage",
                "display_name" => "Manage KDS Settings",
                "group_name" => "KDS Settings",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "customers.view",
                "display_name" => "View Customers",
                "group_name" => "Customers",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "customers.manage",
                "display_name" => "Manage Customers",
                "group_name" => "Customers",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "reservations.view",
                "display_name" => "View Reservations",
                "group_name" => "Reservations",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reservations.manage",
                "display_name" => "Manage Reservations",
                "group_name" => "Reservations",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "reservations.view",
                "display_name" => "View Reservations",
                "group_name" => "Reservations",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reservations.manage",
                "display_name" => "Manage Reservations",
                "group_name" => "Reservations",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reports.daily-report",
                "display_name" => "Daily Sales Report",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reports.batch-report",
                "display_name" => "Batch Report",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reports.batch-report-detail",
                "display_name" => "Batch Report Detail",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reports.shift-drawer-report",
                "display_name" => "Shift Drawer Report",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reports.employee-sales-summary",
                "display_name" => "Employee Sales Summary",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reports.clockIn-report",
                "display_name" => "Clock In Report",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reports.sales-summary-report",
                "display_name" => "Sales Summary Report",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "reports.shift-summary-report",
                "display_name" => "Shift Summary Report",
                "group_name" => "Reports",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "settings.view",
                "display_name" => "View Settings",
                "group_name" => "Settings",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "settings.save",
                "display_name" => "Save Settings",
                "group_name" => "Settings",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "staff.view",
                "display_name" => "View Staff",
                "group_name" => "Staff",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "shift.handle",
                "display_name" => "Open/Close Shift",
                "group_name" => "Shift Management",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "employee-clockout",
                "display_name" => "Employee Clock Out",
                "group_name" => "Employee Clock Out",
                "guard_name" => "api",
                "permission_type" => 2
            ],

            [
                "name" => "activity-log.view",
                "display_name" => "View Activity Log",
                "group_name" => "Activity Log",
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
