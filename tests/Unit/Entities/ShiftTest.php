<?php

namespace App\Tests\Unit\Entities;

use App\Models\Shift;
use App\Modules\Shifts\Entities\Shift as ShiftEntity;
use App\Modules\Shifts\Entities\Staff;
use PHPUnit\Framework\TestCase;

class ShiftTest extends TestCase
{
    public function testConstructor()
    {
        $shift = $this->getMockBuilder(Shift::class)->disableOriginalConstructor()->getMock();
        $staff = $this->getMockBuilder(Staff::class)->disableOriginalConstructor()->getMock();

        $entity = new ShiftEntity($staff, $shift);
        $this->assertInstanceOf(ShiftEntity::class, $entity);

    }

    public function testGetters()
    {
        $staff = $this->getMockBuilder(Staff::class)->disableOriginalConstructor()->getMock();
        $shift = (new Shift())->setRawAttributes(['starttime' => '12', 'endtime' => '19', 'workhours' => 6]);

        $entity = new ShiftEntity($staff, $shift);
        $this->assertEquals($staff, $entity->getStaff());
        $this->assertEquals('12', $entity->getStartTime());
        $this->assertEquals('19', $entity->getEndTime());
        $this->assertEquals(6, $entity->getWorkHours());

    }
}
