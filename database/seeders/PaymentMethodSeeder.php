<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentMethod::insert(
            [
                'name' => 'Cash',
                'type' => 'cash',
                'status' => 1,
                'is_deletable' => 0, // This ensures "Cash" cannot be deleted
                'created_by' => 1, // Assuming Super Admin has ID 1
            ]
        );

        PaymentMethod::insert(
            [
                'name' => 'Delivery Partner',
                'type' => 'delivery_partner',
                'status' => 1,
                'is_deletable' => 0, // This ensures cannot be deleted
                'created_by' => 1, // Assuming Super Admin has ID 1
            ]
        );
    }
}
