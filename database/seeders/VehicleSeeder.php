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
        $vehicleData = [
            [
                'vehicle_type' => 'Honda',
                'vehicle_name' => 'Honda FD',
                'year' => '2018',
                'price' => '50000.00',
                'is_available' => true,
            ],
            [
                'vehicle_type' => 'Toyota',
                'vehicle_name' => 'Camry',
                'year' => '2020',
                'price' => '55000.00',
                'is_available' => true,
            ],
            [
                'vehicle_type' => 'Ford',
                'vehicle_name' => 'Mustang',
                'year' => '2019',
                'price' => '60000.00',
                'is_available' => false,
            ],
        ];

        foreach ($vehicleData as $data) {
            DB::table('vehicle')->insert([
                'vehicle_type' => $data['vehicle_type'],
                'vehicle_name' => $data['vehicle_name'],
                'year' => $data['year'],
                'price' => $data['price'],
                'is_available' => $data['is_available'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

