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
        $registerInfo = [
            [
                'vehicle_id' => 1,
                'reg_no' => 'ABC 123',
                'state_reg_no' => 'CALIFORNIA',
                'register_date' => now(),
                'register_fee' => 250,
                'is_active' => true,
            ],
            [
                'vehicle_id' => 2,
                'reg_no' => 'XYZ 789',
                'state_reg_no' => 'NEW YORK',
                'register_date' => now(),
                'register_fee' => 350,
                'is_active' => true,
            ],
            [
                'vehicle_id' => 3,
                'reg_no' => 'EFG 456',
                'state_reg_no' => 'TEXAS',
                'register_date' => now(),
                'register_fee' => 400,
                'is_active' => false,
            ],
        ];

        foreach ($registerInfo as $info) {
            DB::table('vehicle_register')->insert($info);
        }
    }
}

