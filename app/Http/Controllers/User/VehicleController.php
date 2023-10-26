<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function getInfoVehicle()
    {
        $vehicles = Vehicle::with('vehicleRegister')->get();

        $formattedDetails = $vehicles->map(function ($vehicle) {
            $register = $vehicle->vehicleRegister->first();

            return [
                'id' => $vehicle->id,
                'Vehicle Registration Number' => $register->reg_no,
                'Vehicle Brand' => $vehicle->vehicle_type,
                'Vehicle Name' => $vehicle->vehicle_name,
                'Vehicle Plate State' => $register->state_reg_no,
                'Vehicle Year Release' => $register->register_date,
                'Is Available' => $vehicle->is_available ? 'Yes' : 'No',
                'Amount' => 'RM ' . number_format($vehicle->price, 2),
            ];
        });

        return Inertia::render('PageVehicle/listvehicle', [
            'vehicle' => $formattedDetails,
        ]);
    }

    public function updateAvailable(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $vehicle->update(['is_available' => $request->input('isAvailable')]);

        return response()->json(['message' => 'Ok'], 200);
    }

    public function storeVehicle(Request $request)
    {
        $validate = $this->validate($request, [
            'vehicleBrand' => 'required|string',
            'vehicleName' => 'required|string',
            'vehiclePlatState' => 'required|string',
            'vehicleYearRelease' => 'required|date',
            'isAvailable' => 'required|boolean',
            'price' => 'required|numeric',
        ]);

        Vehicle::create($validate);

        return response()->json(['message' => 'Success'], 200);
    }
}
