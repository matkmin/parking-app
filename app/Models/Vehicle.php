<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $table = 'vehicle';

    protected $fillable = [
        'vehicle_type',
        'vehicle_name',
        'year',
        'price',
        'is_available',
    ];

    public function vehicleRegister()
    {
        return $this->hasMany( RegisterVehicle::class);
    }
}
