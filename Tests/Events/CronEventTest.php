<?php

namespace Litvinab\Bundle\CronEventBundle\Tests\Events;

use Litvinab\Api10Bundle\Tests\TestCase\LitvinabServiceTestCase;
use Litvinab\Bundle\CronEventBundle\Events\CronEvent;

/**
 * Class CronEventTest
 *
 * @package Litvinab\Bundle\CronEventBundle\Tests\Events
 */
class CronEventTest extends LitvinabServiceTestCase
{
    /**
     * Test get schedule
     */
    public function testGetSchedule()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel');

        $cronEvent = new CronEvent($model);
        $cronEvent->setSchedule($scheduleDocument);

        $this->assertEquals($scheduleDocument, $cronEvent->getSchedule());
    }

    /**
     * Test enable schedule
     */
    public function testEnable()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['enableSchedule']);

        $cronEvent = new CronEvent($model);
        $cronEvent->setSchedule($scheduleDocument);

        $cronEvent->enable();

        $this->assertTrue(true);
    }

    /**
     * Test disable schedule
     */
    public function testDisable()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['disableSchedule']);

        $cronEvent = new CronEvent($model);
        $cronEvent->setSchedule($scheduleDocument);

        $cronEvent->disable();

        $this->assertTrue(true);
    }

    /**
     * Test delete schedule
     */
    public function testDelete()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['removeSchedule']);

        $cronEvent = new CronEvent($model);
        $cronEvent->setSchedule($scheduleDocument);

        $cronEvent->delete();

        $this->assertTrue(true);
    }

} 