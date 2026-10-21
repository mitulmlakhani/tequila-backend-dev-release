<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UnitOfMeasurement;

class UnitOfMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $unitOfMeasurements = UnitOfMeasurement::insert([
            [
                'name' => 'Grams',
                'status' => true
            ],
            [
                'name' => 'kg',
                'status' => true
            ],
            [
                'name' => 'Inches',
                'status' => true
            ],
            [
                'name' => 'Litre',
                'status' => true
            ],
            [
                'name' => 'ML',
                'status' => true
            ],
            [
                'name' => 'Ounces',
                'status' => true
            ],
            [
                'name' => 'Serves',
                'status' => true
            ],
            [
                'name' => 'Slice',
                'status' => true
            ],
            [
                'name' => 'CMS',
                'status' => true
            ],
            [
                'name' => 'Piece',
                'status' => true
            ],
            [
                'name' => 'Scoop',
                'status' => true
            ],
            [
                'name' => 'LBS',
                'status' => true
            ],
            [
                'name' => 'Pack',
                'status' => true
            ],
            [
                'name' => 'Bottle',
                'status' => true
            ]
        ]);
    }
}
