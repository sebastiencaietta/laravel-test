<?php

namespace App\Test\Unit\Calculators;

use App\Models\Shift as ShiftModel;
use App\Modules\Shifts\Calculators\MinutesAlone;
use App\Modules\Shifts\Calculators\TotalHours;
use App\Modules\Shifts\Entities\Shift;
use App\Modules\Shifts\Entities\Staff;
use DateTime;
use Illuminate\Support\Collection;
use PHPUnit\Framework\TestCase;

class MinutesAloneTest extends TestCase
{
    public function aloneTimeFrameProvider()
    {
        return [
            [
                [new DateTime('12:00:00'), new DateTime('19:00:00')], //timeframesA
                [new DateTime('15:00:00'), new DateTime('19:00:00')], //timeframeB
                [[new DateTime('12:00:00'), new DateTime('15:00:00')]], //expected result timeframes
            ],
            [
                [new DateTime('12:00:00'), new DateTime('19:00:00')],
                [new DateTime('12:00:00'), new DateTime('15:00:00')],
                [[new DateTime('15:00:00'), new DateTime('19:00:00')]],
            ],
            [
                [new DateTime('12:00:00'), new DateTime('19:00:00')],
                [new DateTime('09:00:00'), new DateTime('15:00:00')],
                [[new DateTime('15:00:00'), new DateTime('19:00:00')]],
            ],
            [
                [new DateTime('12:00:00'), new DateTime('19:00:00')],
                [new DateTime('15:00:00'), new DateTime('22:00:00')],
                [[new DateTime('12:00:00'), new DateTime('15:00:00')]],
            ],
            [
                [new DateTime('15:00:00'), new DateTime('17:00:00')],
                [new DateTime('14:00:00'), new DateTime('18:00:00')],
                [],
            ],
            [
                [new DateTime('12:00:00'), new DateTime('19:00:00')],
                [new DateTime('18:00:00'), (new DateTime('03:00:00'))->modify('+1 day')],
                [[new DateTime('12:00:00'), new DateTime('18:00:00')]],
            ],
            [
                [new DateTime('12:00:00'), new DateTime('19:00:00')],
                [new DateTime('12:00:00'), (new DateTime('19:00:00'))],
                [],
            ],
            [
                [new DateTime('12:00:00'), new DateTime('19:00:00')],
                [new DateTime('14:00:00'), new DateTime('16:00:00')],
                [[new DateTime('12:00:00'), new DateTime('14:00:00')], [new DateTime('16:00:00'), new DateTime('19:00:00')]],
            ]
        ];
    }

    public function getTimeFrameFromStringsProvider()
    {
        return [
            [
                '12:00:00',
                '19:00:00',
                [new DateTime('12:00:00'), new DateTime('19:00:00')],
            ],
            [
                '03:00:00',
                '15:00:00',
                [new DateTime('03:00:00'), new DateTime('15:00:00')],
            ],
            [
                '19:00:00',
                '05:00:00',
                [new DateTime('19:00:00'), (new DateTime('05:00:00'))->modify('+ 1day')],
            ],
        ];
    }

    /**
     * @dataProvider aloneTimeFrameProvider
     * @param array $timeframeA
     * @param array $timeframeB
     * @param array $expected
     */
    public function testGetAloneTimeFrames(array $timeframeA, array $timeframeB, array $expected)
    {
        $calculator = new MinutesAlone();
        $result = $calculator->testGetAloneTimeFrames($timeframeA, $timeframeB);
        $this->assertEquals($expected, $result);
    }

    /**
     * @dataProvider getTimeFrameFromStringsProvider
     * @param string $start
     * @param string $end
     * @param array $expected
     */
    public function testGetTimeFrameFromStrings(string $start, string $end, array $expected)
    {
        $calculator = new MinutesAlone();
        $result = $calculator->getTimeFrameFromStrings($start, $end);
        $this->assertEquals($expected, $result);
    }
}
