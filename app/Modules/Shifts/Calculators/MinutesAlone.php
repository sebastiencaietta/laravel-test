<?php

namespace App\Modules\Shifts\Calculators;

use App\Modules\Shifts\Entities\Shift;
use DateTime;
use Illuminate\Support\Collection;

class MinutesAlone implements Calculator
{
    public function calculate(Collection $shifts): float
    {
        $total = 0;

        /** @var Shift $shiftA */
        /** @var Shift $shiftB */
        foreach ($shifts as $shiftA) {
            $timeframeA = $this->getTimeFrameFromStrings($shiftA->getStartTime(), $shiftA->getEndTime());
            $timeframesWorkedAlone = [$timeframeA];

            $timeframesWorkedAlone = $this->compareOriginalWithOtherShifts($shiftA, $timeframesWorkedAlone, $shifts);
            if ($timeframesWorkedAlone === []) {
                continue;
            }
            $total += $this->getMinutesWorkedAlone($timeframesWorkedAlone);
        }

        return $total;
    }

    public function compareOriginalWithOtherShifts(Shift $originalShift, array $timeframes, Collection $shifts): array
    {
        foreach ($shifts as $shiftB) {
            if ($originalShift === $shiftB) {
                continue;
            }
            $timeframeB = $this->getTimeFrameFromStrings($shiftB->getStartTime(), $shiftB->getEndTime());

            foreach ($timeframes as $timeframeA) {
                $timeframes = $this->getAloneTimeFrames($timeframeA, $timeframeB);
            }
            if ($timeframes === []) {
                return [];
            }
        }
        return $timeframes;
    }

    public function getTimeFrameFromStrings($start, $end): array
    {
        $dateStart = new DateTime($start);
        $dateEnd = new DateTime($end);
        if ($dateEnd < $dateStart) {
            $dateEnd->modify('+1 day');
        }

        return [$dateStart, $dateEnd];
    }

    public function getAloneTimeFrames($timeframeA, $timeframeB): array
    {
        list($startA, $endA) = $timeframeA;
        list($startB, $endB) = $timeframeB;

        if ($startA > $endB || $endA < $startB) { //If the dates don't overlap, A worked without B the whole time
            return [$timeframeA];
        }

        //B worked before and after A or the same time as A, so A never worked alone during this timeframe
        if ($startB <= $startA && $endB >= $endA || ($startA == $startB && $endA == $endB)) {
            return [];
        }

        if ($startB > $startA && $endB < $endA) { //B worked during A, A worked alone before B arrived and after he left
            return [[$startA, $startB], [$endB, $endA]];
        }

        //A and B timeframes overlap, A worked alone when B wasn't working
        if ($startB <= $startA) {
            return [[$endB, $endA]];
        } else {
            return [[$startA, $startB]];
        }
    }

    public function getMinutesWorkedAlone(array $timeframes): float
    {
        $total = 0;
        foreach ($timeframes as $timeframe) {
            list($start, $end) = $timeframe;
            $secondsWorkedAlone = $end->getTimestamp() - $start->getTimestamp();
            $total += $secondsWorkedAlone / 60;
        }
        return $total;
    }
}
