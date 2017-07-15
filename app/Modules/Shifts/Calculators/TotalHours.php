<?php

namespace App\Modules\Shifts\Calculators;

use App\Modules\Shifts\Entities\Shift;
use Illuminate\Support\Collection;

class TotalHours implements Calculator
{
    public function calculate(Collection $shifts): int
    {
        $totalHours = 0;
        /** @var Shift $shift */
        foreach ($shifts as $shift) {
            $totalHours += $shift->getWorkHours();
        }
        return $totalHours;
    }
}
