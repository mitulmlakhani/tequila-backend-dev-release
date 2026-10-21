<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([

            CreateSuperAdminUserSeeder::class,
            RolesSeeder::class,
            UnitOfMeasurementSeeder::class,
            OrderStatusSeeder::class,
            TimezonesTableSeeder::class,
            
            // Permissions Seeders
            PermissionsSeeder::class,
            CategorySchedulerPermissionSeeder::class,
            DevicePermissionSeeder::class,
            EmployeeClockOutPermissionSeeder::class,
            GiftCardPermisionsSeeder::class,
            GiftVoucherPermissionsSeeder::class,
            ImportPermissionsSeeder::class,
            IngredientInventoryPermissionsSeeder::class,
            ItemInventoryPermissionsSeeder::class,
            ItemPriceEditorPermissionsSeeder::class,
            ItemTypePermissionsSeeder::class,
            NewApiPermissionsSeeder::class,
            PaymentHistorySeeder::class,
            PaymentMethodSeeder::class,
            PaymentPermissionsSeeder::class,
            PermissionSeeder::class,
            ReportNotificationPermissionSeeder::class,
            EmailAndEmployeeSalesSummaryReportPermissionSeeder::class,
            SalesShiftSummaryReportPermissionSeeder::class,
            SettingPermissions::class,
            ShiftDetailsPermissionSeeder::class,
            ShiftModulePermissions::class,
            TerminalPermisionsSeeder::class,
            TicketHistorySeeder::class,
            TipSettingPermissionsSeeder::class,
            UnitOfMeasurementSeeder::class,
            VendorInvoiceSeeder::class,
            VendorPermissionSeeder::class,


        ]);
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
