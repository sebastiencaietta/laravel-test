<?php

namespace App\Modules\Shifts\Repositories;

use App\Models\Shift;
use App\Modules\Shifts\Calculators\MinutesAlone;
use App\Modules\Shifts\Calculators\TotalHours;
use App\Modules\Shifts\Entities\Shift as ShiftEntity;
use App\Modules\Shifts\Entities\Staff;
use App\Modules\Shifts\Entities\WorkDay;
use App\Modules\Shifts\Sorting\ShiftsPerDay;
use Illuminate\Support\Collection;

class ShiftRepository
{
    protected $model;

    protected $shifts;

    protected $shiftsPerDay;

    protected $totalCalculator;

    protected $aloneCalculator;

    public function __construct(
        Shift $shiftModel,
        ShiftsPerDay $shiftsPerDay,
        TotalHours $totalCalculator,
        MinutesAlone $aloneCalculator
    ) {
        $this->model = $shiftModel;
        $this->shiftsPerDay = $shiftsPerDay;
        $this->totalCalculator = $totalCalculator;
        $this->aloneCalculator = $aloneCalculator;
    }

    public function getWorkDaysShifts(): Collection
    {
        $shifts = $this->model->getEmployeesShifts();

        return $this->createWorkDays($shifts);
    }

    public function getEmployeeIds(): array
    {
        $resultCollection = $this->model->getEmployeeIds();
        return array_map(
            function ($result) {
                return $result['staffid'];
            },
            $resultCollection->toArray()
        );
    }

    private function createWorkDays(Collection $shifts): Collection
    {
        $shiftsByDay = $this->shiftsPerDay->sort($shifts);
        $workDays = [];
        foreach ($shiftsByDay as $dayNr => $shiftsOfTheDay) {
            foreach ($shiftsOfTheDay as $shiftData) {
                $staff = new Staff($shiftData->staffid);
                $shift = new ShiftEntity($staff, $shiftData);
                $day = $workDays[$dayNr] ?? $this->createWorkDay()->setDayNr($shiftData->daynumber);
                $day->addShift($shift);
                $workDays[$dayNr] = $day;
            }
        }
        return new Collection($workDays);
    }

    public function createWorkDay()
    {
        return new WorkDay($this->totalCalculator, $this->aloneCalculator);
    }
}
