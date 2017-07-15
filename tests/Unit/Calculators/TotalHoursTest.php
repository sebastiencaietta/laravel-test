<?php

namespace App\Test\Unit\Calculators;

use App\Models\Shift as ShiftModel;
use App\Modules\Shifts\Calculators\TotalHours;
use App\Modules\Shifts\Entities\Shift;
use App\Modules\Shifts\Entities\Staff;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class TotalHoursTest extends TestCase
{

    public function testCalculate()
    {
        $calculator = new TotalHours();

        $shifts = new Collection(
            [
                new Shift(new Staff(0), (new ShiftModel())->setRawAttributes(['workhours' => 2])),
                new Shift(new Staff(1), (new ShiftModel())->setRawAttributes(['workhours' => 3])),
                new Shift(new Staff(2), (new ShiftModel())->setRawAttributes(['workhours' => 4])),
                new Shift(new Staff(3), (new ShiftModel())->setRawAttributes(['workhours' => 5])),
            ]
        );
        $total = $calculator->calculate($shifts);
        $this->assertEquals(2+3+4+5, $total);
    }

}
