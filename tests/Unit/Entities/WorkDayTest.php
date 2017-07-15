<?php

namespace App\Tests\Unit\Entities;

use App\Models\Shift as ShiftModel;
use App\Modules\Shifts\Calculators\MinutesAlone;
use App\Modules\Shifts\Calculators\TotalHours;
use App\Modules\Shifts\Entities\Shift;
use App\Modules\Shifts\Entities\Staff;
use App\Modules\Shifts\Entities\WorkDay;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class WorkDayTest extends TestCase
{
    public function testConstructor()
    {
        $totalHoursMock = $this->getMockBuilder(TotalHours::class)->getMock();
        $minutesAloneMock = $this->getMockBuilder(MinutesAlone::class)->getMock();
        $workDay = new WorkDay($totalHoursMock, $minutesAloneMock);

        $this->assertInstanceOf(WorkDay::class, $workDay);
        $this->assertEquals(new Collection([]), $workDay->getShifts());
    }

    public function testFluentSetters()
    {
        $totalHoursMock = $this->getMockBuilder(TotalHours::class)->getMock();
        $minutesAloneMock = $this->getMockBuilder(MinutesAlone::class)->getMock();
        $workDay = new WorkDay($totalHoursMock, $minutesAloneMock);

        $this->assertInstanceOf(WorkDay::class, $workDay->setDayNr(1));
        $this->assertInstanceOf(WorkDay::class, $workDay->setShifts(new Collection([])));
    }

    public function testAddShift()
    {
        $totalHoursMock = $this->getMockBuilder(TotalHours::class)->getMock();
        $minutesAloneMock = $this->getMockBuilder(MinutesAlone::class)->getMock();
        $workDay = new WorkDay($totalHoursMock, $minutesAloneMock);
        $shift = $this->getMockBuilder(Shift::class)->disableOriginalConstructor()->getMock();
        $workDay->addShift($shift);
        $this->assertEquals(new Collection([$shift]), $workDay->getShifts());
    }

    public function testGetters()
    {
        $totalHoursMock = $this->getMockBuilder(TotalHours::class)->setMethods(['calculate'])->getMock();
        $minutesAloneMock = $this->getMockBuilder(MinutesAlone::class)->setMethods(['calculate'])->getMock();
        $totalHoursMock->expects($this->once())->method('calculate')->willReturn(0);
        $minutesAloneMock->expects($this->once())->method('calculate')->willReturn(0);
        $workDay = new WorkDay($totalHoursMock, $minutesAloneMock);
        $workDay->setDayNr(0);
        $shift = new Shift(new Staff(1), new ShiftModel());
        $workDay->setShifts(new Collection([$shift]));

        $this->assertEquals(0, $workDay->getTotalHours());
        $this->assertEquals(0, $workDay->getMinutesWorkedAlone());
        $this->assertEquals(0, $workDay->getTotalHours());
        $this->assertEquals(0, $workDay->getMinutesWorkedAlone());
        $this->assertEquals(0, $workDay->getDayNr());
        $this->assertNull($workDay->getShiftForEmployee(10));
        $this->assertEquals($shift, $workDay->getShiftForEmployee(1));
        $this->assertEquals($totalHoursMock, $workDay->getTotalCalculator());
        $this->assertEquals($minutesAloneMock, $workDay->getAloneCalculator());
    }
}
