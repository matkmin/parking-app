<?php

namespace App\Services;

use App\Models\Vehicle;
use Illuminate\Support\Facades\DB;

class vehicleService
{

    public function createVehicle(array $attributes): Vehicle
    {
        return DB::transaction(function ()  use ($attributes) {
            $vehicle = Vehicle::create([
                'vehicle_name' => $attributes['vehicle_name'],
                'vehicle_type' => $attributes['vehicle_type'],
                'year' => '2023',
                'price' => $attributes['price'],
                'is_available' => $attributes['is_available'],
            ]);
            dd($vehicle);
            return $vehicle;
        });
    }

    public function updateVehicle(Vehicle $vehicle, array $attributes): Vehicle
    {
        $vehicle->update([
            'vehicle_name' => $attributes['vehicle_name'],
            'vehicle_type' => $attributes['vehicle_type'],
            'year' => $attributes['year'],
            'price' => $attributes['price'],
            'is_available' => $attributes['is_available'],
        ]);
        // $vehicle->fill($attributes)->update();
        return $vehicle;
    }
}
