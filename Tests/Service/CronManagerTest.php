<?php


namespace Litvinab\Bundle\CronEventBundle\Tests\Service;

use Litvinab\Api10Bundle\Tests\TestCase\LitvinabServiceTestCase;
use Litvinab\Bundle\CronEventBundle\Model\ScheduleModel;
use Litvinab\Bundle\CronEventBundle\Service\CronManager;

/**
 * Class CronManagerTest
 *
 * @package Litvinab\Bundle\CronEventBundle\Tests\Service
 */
class CronManagerTest extends LitvinabServiceTestCase
{
    /**
     * Test run schedules
     */
    public function testRunSchedules()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['updateAllTypeStatus', 'getExpiredAndEnabledSchedules']);
        $this->setMockMethodsReturnValue($model, ['getExpiredAndEnabledSchedules'], [$scheduleDocument]);
        $dispatcher = $this->getMockObject('Symfony\Component\EventDispatcher\EventDispatcher', ['dispatch']);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);
        $cronManager->setDispatcher($dispatcher);

        $cronManager->runSchedules();

        $this->assertTrue(true);
    }

    /**
     * Test get all schedules
     */
    public function testGetSchedules()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['getAllSchedules']);
        $this->setMockMethodsReturnValue($model, ['getAllSchedules'], [$scheduleDocument]);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $result = $cronManager->getSchedules();

        $this->assertCount(1, $result);
        $this->assertContains($scheduleDocument, $result);
    }

    /**
     * Test get schedules by ad id
     */
    public function testGetSchedulesByAdId()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['getSchedulesByAdId']);
        $this->setMockMethodsReturnValue($model, ['getSchedulesByAdId'], [$scheduleDocument]);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $result = $cronManager->getSchedulesByAdId('test');

        $this->assertCount(1, $result);
        $this->assertContains($scheduleDocument, $result);
    }

    /**
     * Test update schedule
     */
    public function testUpdateSchedule()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['save']);
        $this->setMockMethodsReturnValue($model, ['save'], $scheduleDocument);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $result = $cronManager->updateSchedule($scheduleDocument);

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test set timer
     */
    public function testSetTimer()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['createScheduleTimer']);
        $this->setMockMethodsReturnValue($model, ['createScheduleTimer'], $scheduleDocument);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $result = $cronManager->setTimer('test', 'test', 500);

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test set event
     */
    public function testSetEvent()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['createScheduleEvent']);
        $this->setMockMethodsReturnValue($model, ['createScheduleEvent'], $scheduleDocument);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $result = $cronManager->setEvent('test', 'test', '20-04-2015');

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test delete timer
     */
    public function deleteTimer()
    {
        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['removeTimer']);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $cronManager->deleteTimer('test');

        $this->assertTrue(true);
    }

    /**
     * Test delete event
     */
    public function deleteEvent()
    {
        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['removeEvent']);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $cronManager->deleteEvent('test');

        $this->assertTrue(true);
    }

    /**
     * Test find Schedule by ad id
     */
    public function testFindScheduleByAdId()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['findScheduleByAdId']);
        $this->setMockMethodsReturnValue($model, ['findScheduleByAdId'], $scheduleDocument);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $result = $cronManager->findScheduleByAdId('test');

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test set event
     */
    public function testDeleteSchedule()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $model = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Model\ScheduleModel', ['removeSchedule']);

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $result = $cronManager->deleteSchedule($scheduleDocument);

        $this->assertTrue($result);
    }

    /**
     * Test delete timer
     */
    public function testDeleteTimer()
    {
        $model = $this->getMockBuilder(ScheduleModel::class)->disableOriginalConstructor()
            ->setMethods(['removeTimer'])
            ->getMock();
        $model->expects($this->once())->method('removeTimer')->with($this->equalTo('timerName'));

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $cronManager->deleteTimer('timerName');
    }

    /**
     * Test delete event
     */
    public function testDeleteEvent()
    {
        $model = $this->getMockBuilder(ScheduleModel::class)->disableOriginalConstructor()
            ->setMethods(['removeEvent'])
            ->getMock();
        $model->expects($this->once())->method('removeEvent')->with($this->equalTo('eventName'));

        $cronManager = new CronManager();
        $cronManager->setScheduleModel($model);

        $cronManager->deleteEvent('eventName');
    }
} 