<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    use HasFactory;

    protected $table = 'parking';

    protected $fillable = [
        'vehicle_reg_no', 'entry_time', 'exit_time',
    ];

    public const WEEKDAY_FIRST_3_HOURS_RATE = 3.00;
    public const WEEKDAY_SUBSEQUENT_HOURS_RATE = 1.50;
    public const WEEKDAY_MAX_CHARGE = 20.00;
    public const WEEKKEND_FIRST_3_HOURS_RATE = 5.00;
    public const WEEKKEND_SUBSEQUENT_HOURS_RATE = 2.00;
    public const WEEKEND_MAX_CHARGE = 40.00;
    public const EXIT_WITHIN_15_MIN = 0;
    public const PAYMENT_GRACE_PERIOD_MINUTES = 5;

}
