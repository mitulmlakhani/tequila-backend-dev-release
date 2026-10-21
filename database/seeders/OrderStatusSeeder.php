<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OrderStatus;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define the status names with fixed ids
        $statuses = [
            1 => ['name' => 'Ordered', 'status' => true],
            2 => ['name' => 'Preparing', 'status' => true],
            3 => ['name' => 'Prepared', 'status' => true],
            4 => ['name' => 'Being Served', 'status' => true],
            5 => ['name' => 'Completed', 'status' => true],
            6 => ['name' => 'Canceled', 'status' => true],
            7 => ['name' => 'Payment complete', 'status' => true],
            8 => ['name' => 'Payment Canceled', 'status' => true],
        ];

        // Loop through each status and update or insert it with a fixed id
        foreach ($statuses as $id => $status) {
            OrderStatus::updateOrCreate(
                ['id' => $id], // Use the fixed id (1 to 6)
                $status // The status details (name and status)
            );
        }
    }
}
