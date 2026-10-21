<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Restaurant;

class RestaurantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = Restaurant::create([
            'name' => 'Test Restaurant',
            'address' => 'Test Address',
            'phone' => '(323) 323-2323',
            'email' => 'test@yopmail.com',
            'open_time' => '03:32',
            'close_time' => '04:33',
            'gst_no' => 'IUOI8989YU&865H',
            'dine_in_status' => 'Yes',
            'take_away_status' => 'Yes',
            'delivery_status' => 'Yes',
            'status' => 'Active',
        ]);
    }
}
