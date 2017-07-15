<?php

namespace App\Modules\Shifts\Calculators;

use Illuminate\Support\Collection;

interface Calculator
{
    public function calculate(Collection $shifts);
}
