<?php

declare(strict_types=1);

namespace Tests\Unit;

use Pastock\Pawork\Pawork;
use Tests\TestCase;

class PaworkTest extends TestCase
{
    /**
     * @test
     */
    public function testShouldReturnTrueWhen20170101(): void
    {
        $target = new Pawork();

        $this->assertTrue($target->isHoliday('2017-01-01'));
    }

    /**
     * @test
     */
    public function testShouldReturnFalseWhen20170103(): void
    {
        $target = new Pawork();

        $this->assertFalse($target->isHoliday('2017-01-03'));
    }

    /**
     * @test
     */
    public function testShouldReturnTrueWhenCustomHolidayOn20170103(): void
    {
        $target = new Pawork();
        $target->addHoliday('2017-01-03');

        $this->assertTrue($target->isHoliday('2017-01-03'));
    }

    /**
     * @test
     */
    public function testShouldReturnFalseWhenCustomWorkingDay20170101(): void
    {
        $target = new Pawork();
        $target->addWorkingDay('2017-01-01');

        $this->assertFalse($target->isHoliday('2017-01-01'));
    }
}
