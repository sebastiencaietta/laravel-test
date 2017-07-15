<?php

namespace App\Tests\Unit\Repositories;

use App\Models\Shift;
use App\Modules\Shifts\Calculators\MinutesAlone;
use App\Modules\Shifts\Calculators\TotalHours;
use App\Modules\Shifts\Entities\Shift as ShiftEntity;
use App\Modules\Shifts\Entities\Staff;
use App\Modules\Shifts\Entities\WorkDay;
use App\Modules\Shifts\Repositories\ShiftRepository;
use App\Modules\Shifts\Sorting\ShiftsPerDay;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;
use PHPUnit\Framework\TestCase;

class ShiftRepositoryTest extends TestCase
{
    protected $totalHoursMock;

    protected $minutesAloneMock;

    public function setUp()
    {
        $this->totalHoursMock = $this->getMockBuilder(TotalHours::class)->getMock();
        $this->minutesAloneMock = $this->getMockBuilder(MinutesAlone::class)->getMock();
    }

    public function testGetEmployeesIds()
    {
        $modelMock = $this->getMockBuilder(Shift::class)->setMethods(['getEmployeeIds'])->getMock();
        $sorterMock = $this->getMockBuilder(ShiftsPerDay::class)->getMock();

        $employeeIds = [
            ['staffid' => 1],
            ['staffid' => 2],
            ['staffid' => 3],
            ['staffid' => 4],
        ];
        $modelMock->expects($this->once())->method('getEmployeeIds')->willReturn(new Collection($employeeIds));

        $repository = new ShiftRepository($modelMock, $sorterMock, $this->totalHoursMock, $this->minutesAloneMock);
        $result = $repository->getEmployeeIds();
        $this->assertEquals([1, 2, 3, 4], $result);
    }

    public function testGetWorkDaysShiftsReturnsEmptyCollection()
    {
        $modelMock = $this->getMockBuilder(Shift::class)->setMethods(['getEmployeesShifts'])->getMock();
        $sorterMock = $this->getMockBuilder(ShiftsPerDay::class)->setMethods(['sort'])->getMock();

        $modelMock->expects($this->once())->method('getEmployeesShifts')->willReturn(new Collection([]));
        $sorterMock->expects($this->once())->method('sort')->willReturn([]);
        $repository = new ShiftRepository($modelMock, $sorterMock, $this->totalHoursMock, $this->minutesAloneMock);

        $result = $repository->getWorkDaysShifts();
        $this->assertEquals([], $result->toArray());
    }

    public function testGetWorkDaysShiftsReturnsCollectionOfWorkDays()
    {
        $modelMock = $this->getMockBuilder(Shift::class)->setMethods(['getEmployeesShifts'])->getMock();
        $sorterMock = $this->getMockBuilder(ShiftsPerDay::class)->setMethods(['sort'])->getMock();

        $shiftModels = [
            (new Shift())->setRawAttributes(['staffid' => 1, 'daynumber' => 0]),
            (new Shift())->setRawAttributes(['staffid' => 2, 'daynumber' => 0]),
            (new Shift())->setRawAttributes(['staffid' => 3, 'daynumber' => 0]),
            (new Shift())->setRawAttributes(['staffid' => 2, 'daynumber' => 1]),
            (new Shift())->setRawAttributes(['staffid' => 3, 'daynumber' => 1]),
            (new Shift())->setRawAttributes(['staffid' => 4, 'daynumber' => 1]),
        ];
        $shiftsPerDay = [
            [
                $shiftModels[0],
                $shiftModels[1],
                $shiftModels[2],
            ],
            [
                $shiftModels[3],
                $shiftModels[4],
                $shiftModels[5],
            ],
        ];

        $modelMock->expects($this->once())->method('getEmployeesShifts')->willReturn(new Collection([]));
        $sorterMock->expects($this->once())->method('sort')->willReturn($shiftsPerDay);
        $repository = new ShiftRepository($modelMock, $sorterMock, $this->totalHoursMock, $this->minutesAloneMock);

        $expected = new SupportCollection([
            (new WorkDay($this->totalHoursMock, $this->minutesAloneMock))->setDayNr(0)->setShifts(new SupportCollection([
                (new ShiftEntity(new Staff(1), $shiftModels[0])),
                (new ShiftEntity(new Staff(2), $shiftModels[1])),
                (new ShiftEntity(new Staff(3), $shiftModels[2])),
            ])),
            (new WorkDay($this->totalHoursMock, $this->minutesAloneMock))->setDayNr(1)->setShifts(new SupportCollection([
                (new ShiftEntity(new Staff(2), $shiftModels[3])),
                (new ShiftEntity(new Staff(3), $shiftModels[4])),
                (new ShiftEntity(new Staff(4), $shiftModels[5])),
            ])),
        ]);

        $result = $repository->getWorkDaysShifts();
        $this->assertEquals($expected, $result);
    }

    public function testCreateWorkDay()
    {
        $modelMock = $this->getMockBuilder(Shift::class)->getMock();
        $sorterMock = $this->getMockBuilder(ShiftsPerDay::class)->getMock();

        $repository = new ShiftRepository($modelMock, $sorterMock, $this->totalHoursMock, $this->minutesAloneMock);
        $workDay = $repository->createWorkDay();
        $this->assertInstanceOf(WorkDay::class, $workDay);
    }
}
