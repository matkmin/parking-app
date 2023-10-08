<?php

namespace App\Http\Controllers\User;

use App\Models\Parking;
use Inertia\Inertia;
use App\Services\ParkingService;
use App\Http\Controllers\Controller;

class ParkingController extends Controller
{

    public function getInfoParking(ParkingService $parkingService)
    {
        // $ListparkingAll = Parking::all();
        $listParking = [];

        foreach (Parking::all() as $record) {
            $vehicleRegNo = $record->vehicle_reg_no;
            $entryTime = $record->entry_time;
            $exitTime = $record->exit_time;
            $duration = $parkingService->calcDuration($entryTime, $exitTime);
            $amountPaid = $parkingService->calcAmountPaid($duration, $exitTime);

            $listParking[] = [
                'vehicleRegNo' => $vehicleRegNo,
                'entryTime' => $entryTime,
                'exitTime' => $exitTime,
                'duration' => $duration,
                'amountPaid' => $amountPaid,
            ];
        }
        // dd($listParkingAll);


        return Inertia::render('PageParking/listpark', [
            'listParking' => $listParking,
        ]);
    }
}
