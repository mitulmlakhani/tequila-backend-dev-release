<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class AddOrderPermissionsForApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipsPermissions = [
            [
                "name" => "tip.view_personal",
                "display_name" => "View Personal Tips",
                "group_name" => "Tips",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "tip.edit_personal",
                "display_name" => "Edit Personal Tips",
                "group_name" => "Tips",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "tip.edit",
                "display_name" => "Edit All Tips",
                "group_name" => "Tips",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "tip.view",
                "display_name" => "View All Tips",
                "group_name" => "Tips",
                "guard_name" => "api",
                "permission_type" => 2
            ],
        ];

        $paymentPermissions = [
            [
                "name" => "payments.view_personal",
                "display_name" => "View Personal Payments",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "payments.accept_card_payments",
                "display_name" => "Accept Card Payment",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "payments.accept_gift_card_payment",
                "display_name" => "Accept Gift Card Payment",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "payments.accept_custom_payment_method",
                "display_name" => "Accept Custom Payment Method",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "payments.refund_personal",
                "display_name" => "Refund Personal Payments",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "payments.view",
                "display_name" => "View All Payments",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "payments.refund",
                "display_name" => "Refund All Payments",
                "group_name" => "Payments",
                "guard_name" => "api",
                "permission_type" => 2
            ]
        ];

        $ordersPermissions = [
            [
                "name" => "orders.view",
                "display_name" => "View All Orders",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.view_personal",
                "display_name" => "View Personal Orders",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.cancel",
                "display_name" => "Cancel All Orders",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
            [
                "name" => "orders.cancel_personal",
                "display_name" => "Cancel Personal Orders",
                "group_name" => "Orders",
                "guard_name" => "api",
                "permission_type" => 2
            ],
        ];

        foreach (
            
            array_merge(
            $tipsPermissions,
            $paymentPermissions,
            $ordersPermissions
        ) 
        
        as $permission) {
            
            $p = Permission::where([
                'name' => $permission['name'],
                'guard_name' => $permission['guard_name'],
                'group_name' => $permission['group_name'],
            ])->firstOrNew();

            $p->name = $permission['name'];
            $p->display_name = $permission['display_name'];
            $p->group_name = $permission['group_name'];
            $p->guard_name = $permission['guard_name'];
            $p->permission_type = $permission['permission_type'];
            $p->save();
        }


    }
}
