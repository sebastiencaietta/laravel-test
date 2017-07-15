<?php

namespace App\Http\Controllers;

use App\Modules\Shifts\Repositories\ShiftRepository;
use Illuminate\View\View;

class ShiftController extends Controller
{

    protected $shiftRepository;

    public function __construct(ShiftRepository $repository)
    {
        $this->shiftRepository = $repository;
    }

    public function index(): View
    {
        $shifts = $this->shiftRepository->getWorkDaysShifts();

        $staffIds = $this->shiftRepository->getEmployeeIds();

        return view('shifts', ['staffIds' => $staffIds, 'shiftsPerDay' => $shifts]);
    }
}
