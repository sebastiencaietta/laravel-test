<?php

namespace App\Test\Unit\Calculators;

use App\Models\Shift;
use App\Modules\Shifts\Sorting\ShiftsPerDay;
use Illuminate\Database\Eloquent\Collection;
use PHPUnit\Framework\TestCase;

class ShiftsPerDayTest extends TestCase
{

    public function testCalculate()
    {
        $sorter = new ShiftsPerDay();
        $shifts = [
            (new Shift())->setRawAttributes(['daynumber' => 0, 'rotaid' => 1]),
            (new Shift())->setRawAttributes(['daynumber' => 1, 'rotaid' => 2]),
            (new Shift())->setRawAttributes(['daynumber' => 0, 'rotaid' => 3]),
            (new Shift())->setRawAttributes(['daynumber' => 1, 'rotaid' => 4]),
            (new Shift())->setRawAttributes(['daynumber' => 2, 'rotaid' => 5]),
        ];
        $collection = new Collection($shifts);


        $result = $sorter->sort($collection);
        $expected = [
            0 => [$shifts[0], $shifts[2]],
            1 => [$shifts[1], $shifts[3]],
            2 => [$shifts[4]],
        ];
        $this->assertEquals($expected, $result);
    }

}
