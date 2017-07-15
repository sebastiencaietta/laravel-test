<?php

namespace App\Modules\Shifts\Sorting;

use App\Models\Shift;
use Illuminate\Database\Eloquent\Collection;

class ShiftsPerDay
{
    public function sort(Collection $shifts)
    {
        $result = [];
        /** @var Shift $shift */
        foreach ($shifts as $shift) {
            $dayNr = $shift->daynumber;
            $result[$dayNr][] = $shift;
        }
        return $result;
    }
}
