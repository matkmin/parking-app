<?php

namespace Database\Seeders;

use App\Models\Parking;
use Illuminate\Database\Seeder;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $infoPark = [
            [
                'vehicle_reg_no' => 'SU 123 K',
                'entry_time' => '2021-10-25 08:16',
                'exit_time' => '2021-10-25 08:27',
            ],
            [
                'vehicle_reg_no' => 'SN 3453 G',
                'entry_time' => '2021-10-25 08:16',
                'exit_time' => '2021-10-25 12:19',
            ],
        ];

        foreach($infoPark as $infoParking){
            Parking::create($infoParking);
        }
    }
}
