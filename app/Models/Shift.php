<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $table = 'rota_slot_staff';

    public function get(): Collection
    {
        return $this->all();
    }

    public function getEmployeesShifts(): Collection
    {
        return $this->newQuery()
            ->whereNotNull('staffid')
            ->where('slottype', 'shift')
            ->get();
    }

    public function getEmployeeIds(): Collection
    {
        return $this->newQuery()
            ->distinct('staffid')
            ->whereNotNull('staffid')
            ->get(['staffid']);
    }
}
