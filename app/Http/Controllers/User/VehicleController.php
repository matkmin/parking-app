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
                'Is Available' => $vehicle->is_available,
                'Amount' => 'RM ' . number_format($vehicle->price, 2),
            ];
        });
        // dd($formattedDetails);
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
    public function searchVehicles(Request $request)
    {
        $input = $request->input('searchInfo');

        dump($input);

        $search = Vehicle::where(function ($q) use ($input) {
            $q->Where('vehicle_type', 'LIKE', '%' . $input . '%')
                ->orWhere('vehicle_name', 'LIKE', '%' . $input . '%');
                // ->orWhere('state_reg_no', 'LIKE', '%' . $input . '%')
                // ->orWhere('register_date', 'LIKE', '%' . $input . '%');
        })
            ->orWhere('reg_no', $input)
            ->get();

        return response()->json($search);
    }

    public function storeVehicle(Request $request)
    {
        // dd($request->all());
        // $validate = $this->validate($request, [
        //     'vehicleBrand' => 'required|string',
        //     'vehicleName' => 'required|string',
        //     'vehiclePlatState' => 'required|string',
        //     'vehicleYearRelease' => 'required|date',
        //     'isAvailable' => 'required|boolean',
        //     'price' => 'required|numeric',
        // ]);

        $validate = $request->all();

        Vehicle::create($validate);

        return response()->json(['message' => 'Success'], 200);
    }
}
