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
        // dd($formattedDuration);
        return $formattedDuration;
    }

    public function calcAmountPaid($duration, $entryTime): float
    {
        $entryDate = Carbon::parse($entryTime);
        $isWeekend = $entryDate->isWeekend();
        $durationParts = explode(' ', $duration);
        // dd($durationParts);
        $hours = intval($durationParts[0]);
        $minutes = intval($durationParts[2]);

        // dd($isWeekend);

        if ($isWeekend) {
            $first3HoursRate = Parking::WEEKKEND_FIRST_3_HOURS_RATE;
            $subsequentHoursRate = Parking::WEEKKEND_SUBSEQUENT_HOURS_RATE;
            $maxCharge = Parking::WEEKEND_MAX_CHARGE;
        } else {
            $first3HoursRate = Parking::WEEKDAY_FIRST_3_HOURS_RATE;
            $subsequentHoursRate = Parking::WEEKDAY_SUBSEQUENT_HOURS_RATE;
            $maxCharge = Parking::WEEKDAY_MAX_CHARGE;
        }

        if ($hours < 1 && $minutes <= 15) {
            return Parking::EXIT_WITHIN_15_MIN;
        }

        $amountPaid = 0;
        // dd($hours);
        // dump($hours  <= 3);
        if ($hours  <= 3) {
            $amountPaid = $hours  * $first3HoursRate;
        } else {
            $amountPaid = 3 * $first3HoursRate;
            // dd($amountPaid);
            $nextDuration = $hours  - 3;
            // dd($nextDuration);
            $amountPaid += $nextDuration *  $subsequentHoursRate;
            // dd($amountPaid);
        }
        // $amountPaid *= 2;
        // dd($amountPaid);

        if ($amountPaid > $maxCharge) {
            $amountPaid = $maxCharge;
        }
        // dd($amountPaid);
        return $amountPaid;
    }
}
