<?php

namespace App\Services;

use App\Models\Parking;
use Illuminate\Support\Carbon;

class ParkingService
{
    public function calcDuration($entryTime, $exitTime)
    {
        $entryTime = Carbon::parse($entryTime);
        $exitTime = Carbon::parse($exitTime);
        $duration = $entryTime->diff($exitTime);
        $formattedDuration = $duration->format('%h hours %i minutes');
        return $formattedDuration;
    }

    public function calcAmountPaid($duration, $entryTime): float
    {
        $entryDate = Carbon::parse($entryTime);
        $isWeekend = $entryDate->isWeekend();
        $durationParts = explode(' ', $duration);
        $hours = intval($durationParts[0]);
        $minutes = intval($durationParts[2]);

        $first3HoursRate = $isWeekend ? Parking::WEEKKEND_FIRST_3_HOURS_RATE : Parking::WEEKDAY_FIRST_3_HOURS_RATE;
        $subsequentHoursRate = $isWeekend ? Parking::WEEKKEND_SUBSEQUENT_HOURS_RATE : Parking::WEEKDAY_SUBSEQUENT_HOURS_RATE;
        $maxCharge = $isWeekend ? Parking::WEEKEND_MAX_CHARGE : Parking::WEEKDAY_MAX_CHARGE;

        if ($hours < 1 && $minutes <= 15) {
            return Parking::EXIT_WITHIN_15_MIN;
        }

        $amountPaid = 0;

        if ($hours  <= 3) {
            $amountPaid = $hours  * $first3HoursRate;
        } else {
            $amountPaid = 3 * $first3HoursRate;
            $nextDuration = $hours  - 3;
            $amountPaid += $nextDuration *  $subsequentHoursRate;
        }

        if ($amountPaid > $maxCharge) {
            $amountPaid = $maxCharge;
        }

        return $amountPaid;
    }
}
