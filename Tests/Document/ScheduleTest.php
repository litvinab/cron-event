<?php

namespace Litvinab\CronEventBundle\Tests\Document;
use Litvinab\CronEventBundle\Document\Schedule;

/**
 * Class ScheduleTest
 *
 * @package Litvinab\CronEventBundle\Tests\Document
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
