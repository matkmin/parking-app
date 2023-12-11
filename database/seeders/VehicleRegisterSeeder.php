<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VehicleRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 100; $i++) {
            $registerInfo = [
                'vehicle_id' => $i,
                'reg_no' => 'ABC ' . rand(100, 999),
                'state_reg_no' => 'STATE ' . rand(1, 50),
                'register_date' => now(),
                'register_fee' => rand(200, 500),
                'is_active' => rand(0, 1) == 1, // Randomly set is_active to true or false
            ];

            DB::table('vehicle_register')->insert($registerInfo);
        }
    }
}
