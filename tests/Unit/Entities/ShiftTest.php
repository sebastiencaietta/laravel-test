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
        $shift = $this->getMockBuilder(Shift::class)->disableOriginalConstructor()->getMock();
        $staff = $this->getMockBuilder(Staff::class)->disableOriginalConstructor()->getMock();

        $entity = new ShiftEntity($staff, $shift);
        $this->assertEquals($staff, $entity->getStaff());

    }
}
