<?php

namespace App\Modules\Shifts\Calculators;

use DateTime;
use Illuminate\Support\Collection;

class MinutesAlone implements Calculator
{
    public function calculate(Collection $shifts)
    {
        $total = 0;
        return $total;
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

    public function testGetAloneTimeFrames($timeframeA, $timeframeB): array
    {
        list($startA, $endA) = $timeframeA;
        list($startB, $endB) = $timeframeB;

        if ($startA > $endB || $endA < $startB) { //If the dates don't overlap, A worked without B the whole time
            return $timeframeA;
        }

        //B worked before and after A or the same time as A, so A never worked alone during this timeframe
        if ($startB < $startA && $endB > $endA || ($startA == $startB && $endA == $endB)) {
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
