<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            $vehicleData = [
                'vehicle_type' => 'Vehicle Type ' . $i,
                'vehicle_name' => 'Vehicle Name ' . $i,
                'year' => rand(2000, 2023), // Random year between 2000 and 2023
                'price' => number_format(rand(20000, 80000) / 100, 2), // Random price with 2 decimal places
                'is_available' => rand(0, 1) == 1, // Randomly set is_available to true or false
                'created_at' => now(),
                'updated_at' => now(),
            ];

            DB::table('vehicle')->insert($vehicleData);
        }
    }
}
