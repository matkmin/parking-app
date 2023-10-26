<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegisterVehicle extends Model
{
    use HasFactory;
    protected $table = 'vehicle_register';

    protected $fillable = [
        'reg_no',
        'state_reg_no',
        'register_date',
        'register_fee',
        'is_active',
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
