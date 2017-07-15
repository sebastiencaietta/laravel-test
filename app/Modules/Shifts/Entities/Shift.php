<?php

namespace App\Modules\Shifts\Entities;

use App\Models\Shift as ShiftModel;

class Shift
{
    /** @var string $startTime */
    protected $startTime;

    /** @var string $endTime */
    protected $endTime;

    /** @var int $workHours */
    protected $workHours;

    protected $staff;

    public function __construct(Staff $staff, ShiftModel $shiftData)
    {
        $this->staff = $staff;
        $this->startTime = $shiftData->starttime;
        $this->endTime = $shiftData->endtime;
        $this->workHours = $shiftData->workhours;
    }

    public function getStaff(): Staff
    {
        return $this->staff;
    }

    public function getStartTime(): string
    {
        return $this->startTime;
    }

    public function getEndTime(): string
    {
        return $this->endTime;
    }

    public function getWorkHours(): int
    {
        return $this->workHours;
    }
}
