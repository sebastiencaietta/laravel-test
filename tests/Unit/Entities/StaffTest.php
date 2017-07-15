<?php

namespace App\Tests\Unit\Entities;

use App\Modules\Shifts\Entities\Staff;
use PHPUnit\Framework\TestCase;

class StaffTest extends TestCase
{
    public function testConstructor()
    {
        $staff = new Staff(1);
        $this->assertInstanceOf(Staff::class, $staff);

    }

    public function testGetters()
    {
        $staff = new Staff(1);
        $this->assertEquals(1, $staff->getId());
    }
}
