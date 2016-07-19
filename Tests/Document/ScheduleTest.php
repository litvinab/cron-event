<?php

namespace Litvinab\Bundle\CronEventBundle\Tests\Document;
use Litvinab\Bundle\CronEventBundle\Document\Schedule;

/**
 * Class ScheduleTest
 *
 * @package Litvinab\Bundle\CronEventBundle\Tests\Document
 */
class ScheduleTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test get id
     */
    public function testGetId()
    {
        $schedule = new Schedule();
        $this->assertNull($schedule->getId());
    }

    /**
     * Test get event
     */
    public function testGetEvent()
    {
        $schedule = new Schedule();
        $schedule->setEvent('event');
        $this->assertEquals('event', $schedule->getEvent());
    }
}
