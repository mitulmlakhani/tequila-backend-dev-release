<?php

return [

    'floors' => 'floors.manage',
    'floors/{floorId}' => 'floors.manage',
    'floor-layouts/{floorId?}' => 'floors.view',
    'floor-layouts/{floorId}' => 'floors.manage',
    'floor-layouts/{floorId}/upload-background-image' => 'floors.manage',

    'floor-layouts/{floorId}/getElements/{elementId?}' => 'floors.view',
    'floor-layouts/{floorId}/elements/{elementId}' => 'floors.manage',
    'floor-layouts/{floorId}/elements' => 'floors.manage',
    'floor-layouts/{floorId}/assign-element-image/{elementId}' => 'floors.manage',
    'floor-layouts/{floorId}/remove-element-image/{elementId}' => 'floors.manage',

    'floor-layouts/{floorId}/elements/{elementId}/chairs/{chairId?}' => 'floors.view',
    'floor-layouts/{floorId}/elements/{elementId}/chairs' => 'floors.manage',
    'floor-layouts/{floorId}/elements/{elementId}/chairs/{chairId}' => 'floors.manage',
    'floor-layouts/{floorId}/tables/{elementId}/chairs/{chairId}' => 'floors.manage',

    'tables/add' => 'floors.manage',
    'tables' => 'floors.view',
    'tables/{tableId}' => 'floors.manage',
    'changestatus/{elementId}' => 'floors.manage',

    'categories' => 'menu.view',
    'items' => 'menu.view',
    'items_mods/{itemId}' => 'menu.view',
    'item_variants/{itemId}' => 'menu.view',
    'categories-schedules' => 'menu.view',
    'happy-hours-settings' => 'menu.view',

    'items/add' => 'menu.manage',
    'items/{id}/update' => 'menu.manage',
    'items/update-soldout' => 'menu.manage',

    'orders' => 'orders.process',
    'orders/{orderId}' => 'orders.process',
    'orders/{parentOrderId}/{subOrderRefNumber}' => 'orders.process',
    'orders/sub_orders' => 'orders.view',
    'parent-orders' => 'orders.view',
    'getCompleteOrderData' => 'orders.view',
    'orders/{orderId}/update-status' => 'orders.modify',
    'orderitem/{subOrderId}/{itemId}' => 'orders.modify',
    'parent_order/combine/{parentOrderId}' => 'orders.split_combine_tickets',
    'sub-order/{subOrderId}' => 'orders.modify',
    'orders/deleted_sub_orders' => 'orders.view',
    'orders/{orderId}/resstore_deleted' => 'orders.modify',

    'orders/transfer_order/{parentRefNumber}' => 'orders.transfer',
    'orders/transfer_order/{parentRefNumber}/{sub_order_ref_number}' => 'orders.transfer',
    'orders/change_table/{subOrderRefNumber}' => 'orders.change_table',
    'taxes' => 'orders.view',
    'tables/{table_id}/active-orders' => 'orders.view',

    '/orders/transfer_order/{parentRefNumber}' => 'orders.transfer',
    '/orders/transfer_order/{parentRefNumber}/{sub_order_ref_number}' => 'orders.transfer',
    '/orders/change_table/{subOrderRefNumber}' => 'orders.change_table',

    'customer' => 'customers.manage',
    'customer/{id}' => 'customers.manage',
    'customers' => 'customers.view',
    'customers/{customerId?}' => 'customers.view',

    'booking' => 'reservations.manage',
    'booking/{bookingId}' => 'reservations.view',

    'report/daily-report' => 'reports.daily-report',
    'report/batch-report' => 'reports.batch-report',
    'report/batch-report-detail/{batch_number}' => 'reports.batch-report-detail',
    'report/batch' => 'reports.batch-report',
    'report/batch/{batchNumber}/detail' => 'reports.batch-report-detail',
    'report/shift_drawer_report' => 'reports.shift-drawer-report',
    'report/employee-sales-summary' => 'reports.employee-sales-summary',
    'report/employee-sales-summary-report' => 'reports.employee-sales-summary',
    'report/clockIn-report' => 'reports.clockIn-report',
    'report/sales-summary-report' => 'reports.sales-summary-report',
    'report/shift-summary-report' => 'reports.shift-summary-report',

    'settings' => 'settings.view',
    'save-settings' => 'settings.save',
    'restaurant_address' => 'settings.view',
    'custom-settings' => 'settings.view',

    'waiters' => 'staff.view',
    'waitersAndCashiers' => 'staff.view',
    'ordered-employees' => 'staff.view',

    'activity-log' => 'activity-log.view',
    
    'crypto-rate/{token}/{to?}/{amount?}' => 'payments.process',
    'crypto-pay-qr' => 'payments.process',
    'order-crypto-payment/{orderId}' => 'payments.process',
    'crypto-check-transaction/{token}/{address}/{timestamp}/{amount}' => 'payments.process',

    'payment-methods' => 'payments.view',
    'payments/{orderId}' => 'payments.process',
    'payments/void/{orderId}' => 'payments.refund',
    'refunds/{orderId}' => 'payments.refund',
    'non-deposit-credit-transactions' => 'payments.view',

    '/tip-batch-complete' => 'tip.distribute',
    'tip-update/{transaction_id}' => 'payments.process',
    'payment/update-post-auth/{transactionId}' => 'payments.process',
    'payment/pending-post-auth-transactions' => 'payments.view',
    '/payment/update-post-auth/bulk' => 'payments.process',
    '/payment/update-post-auth/{transactionId}' => 'payments.process',
    '/payment/pending-post-auth-transactions' => 'payments.process',

    '/giftcard/{code}' => 'giftcard.view',
    '/giftcard/create' => 'giftcard.process',
    '/giftcard/top-up/{cardNumber}' => 'giftcard.process',
    '/giftcard/balance/{cardNumber}' => 'giftcard.process',
    '/giftcard/transactions/{cardNumber}' => 'giftcard.process',

    'tips/distribute-shared-tips' => 'tip.distribute',
    'tips/shift-tips' => 'tip.distribute',
    'tip-batch-complete' => 'tip.distribute',

    'terminal-settings' => 'terminal_settings.view',
    'terminal-settings/{deviceId}' => 'terminal_settings.view',
    'terminal-settings/{terminalId}' => 'terminal_settings.manage',

    'kds/parent-orders' => 'kds.view',
    'kds/parent-orders/{parentOrderId}' => 'kds.view',
    'kds/sub-orders' => 'kds.view',
    'kds/sub-orders/{subOrderId}' => 'kds.view',
    'kds/order-items/{orderItemId}/status' => 'kds.manage',
    'kds/order-items/bulk-update-status' => 'orders.process',
    'kds/parent-orders-items' => 'kds.view',
    'kds/parent-orders-items/{parentOrderId}' => 'kds.view',
    'kds/parent-orders/{parentOrderId}/status' => 'kds.manage',

    'active-shift-employees' => 'employee-clockout',
    'clockout-employee' => 'employee-clockout',

    'shift/start' => 'shift.handle',
    'shift/end' => 'shift.handle',
    'shift/report' => 'shift.handle',
    '/cash-in' => 'shift.handle',
    '/cash-out' => 'shift.handle',
    '/transactions' => 'shift.handle',

    '/report-email-list' => 'reports.send-emails',

    'web-orders/accept' => 'orders.process',
    'web-orders/cancel' => 'orders.process',
    'web-orders/update-status' => 'orders.process',
];
