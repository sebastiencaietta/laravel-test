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
                [new DateTime('12:00:00'), new DateTime('19:00:00')],
                [new DateTime('20:00:00'), new DateTime('22:00:00')],
                [[new DateTime('12:00:00'), new DateTime('19:00:00')]],
            ],
            [
                [new DateTime('13:00:00'), new DateTime('20:00:00')],
                [new DateTime('19:00:00'), (new DateTime('03:00:00'))->modify('+1 day')],
                [[new DateTime('13:00:00'), new DateTime('19:00:00')]],
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
            ],
            [
                [new DateTime('13:00:00'), new DateTime('19:00:00')],
                [new DateTime('11:00:00'), new DateTime('19:00:00')],
                [],
            ],
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

    public function minutesWorkedAloneProvider()
    {
        return [
            [
                [[new DateTime('12:00:00'), new DateTime('12:05:00')]],
                5
            ],
            [
                [[new DateTime('12:00:00'), new DateTime('13:00:00')], [new DateTime('15:00:00'), new DateTime('16:00:00')]],
                120
            ],
            [
                [[new DateTime('12:00:00'), new DateTime('12:00:30')]],
                0.5
            ],
        ];
    }

    public function testCalculate()
    {
        $mockBuilder = $this->getMockBuilder(Shift::class)->disableOriginalConstructor()->setMethods([
            'getStartTime',
            'getEndTime'
        ]);
        $shift1 = $mockBuilder->getMock();
        $shift1->expects($this->any())->method('getStartTime')->willReturn('12:00:00');
        $shift1->expects($this->any())->method('getEndTime')->willReturn('19:00:00');

        $shift2 = $mockBuilder->getMock();
        $shift2->expects($this->any())->method('getStartTime')->willReturn('11:30:00');
        $shift2->expects($this->any())->method('getEndTime')->willReturn('19:00:00');

        $shift3 = $mockBuilder->getMock();
        $shift3->expects($this->any())->method('getStartTime')->willReturn('19:00:00');
        $shift3->expects($this->any())->method('getEndTime')->willReturn('03:00:00');

        $shift4 = $mockBuilder->getMock();
        $shift4->expects($this->any())->method('getStartTime')->willReturn('20:00:00');
        $shift4->expects($this->any())->method('getEndTime')->willReturn('03:00:00');
        $shifts = new Collection([$shift1, $shift2, $shift3, $shift4]);

        $calculator = new MinutesAlone();
        $result = $calculator->calculate($shifts);

        $this->assertEquals(90, $result);
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
        $result = $calculator->getAloneTimeFrames($timeframeA, $timeframeB);
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

    /**
     * @dataProvider minutesWorkedAloneProvider
     * @param array $timeframes
     * @param $expected
     */
    public function testGetMinutesWorkedAlone(array $timeframes, $expected)
    {
        $calculator = new MinutesAlone();
        $result = $calculator->getMinutesWorkedAlone($timeframes);
        $this->assertEquals($expected, $result);
    }
}
