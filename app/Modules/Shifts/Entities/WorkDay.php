<?php

namespace App\Modules\Shifts\Entities;

use App\Modules\Shifts\Calculators\MinutesAlone;
use App\Modules\Shifts\Calculators\TotalHours;
use Illuminate\Support\Collection;

class WorkDay
{
    /** @var int $dayNr */
    protected $dayNr;

    /** @var int $totalHours */
    protected $totalHours;

    /** @var int $minutesWorkedAlone */
    protected $minutesWorkedAlone;

    /** @var Collection $shifts */
    protected $shifts;

    protected $totalCalculator;

    protected $aloneCalculator;

    public function __construct(
        TotalHours $totalCalculator = null,
        MinutesAlone $aloneCalculator = null
    ) {
        $this->shifts = new Collection([]);
        $this->totalCalculator = $totalCalculator;
        $this->aloneCalculator = $aloneCalculator;
    }

    public function addShift(Shift $shift): WorkDay
    {
        $this->shifts->push($shift);
        return $this;
    }

    public function getShiftForEmployee(int $employeeId): ?Shift
    {
        foreach ($this->getShifts() as $shift) {
            if ($shift->getStaff()->getId() === $employeeId) {
                return $shift;
            }
        }
        return null;
    }

    public function getTotalHours(): int
    {
        if (!is_null($this->totalHours)) {
            return $this->totalHours;
        }

        $this->totalHours = $this->totalCalculator->calculate($this->shifts);
        return $this->totalHours;
    }

    public function getMinutesWorkedAlone(): int
    {
        if (!is_null($this->minutesWorkedAlone)) {
            return $this->minutesWorkedAlone;
        }
        $this->minutesWorkedAlone = $this->aloneCalculator->calculate($this->shifts);
        return $this->minutesWorkedAlone;
    }

    public function getShifts(): Collection
    {
        return $this->shifts;
    }

    public function setShifts(Collection $shifts): WorkDay
    {
        $this->shifts = $shifts;
        return $this;
    }

    public function setDayNr(int $dayNr): WorkDay
    {
        $this->dayNr = $dayNr;
        return $this;
    }

    public function getDayNr(): int
    {
        return $this->dayNr ?? 0;
    }

    /**
     * @return TotalHours
     */
    public function getTotalCalculator(): TotalHours
    {
        return $this->totalCalculator;
    }

    /**
     * @return MinutesAlone
     */
    public function getAloneCalculator(): MinutesAlone
    {
        return $this->aloneCalculator;
    }
}
