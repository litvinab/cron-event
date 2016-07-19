<?php


namespace Litvinab\Bundle\CronEventBundle\Tests\Model;

use Litvinab\Api10Bundle\Tests\TestCase\LitvinabServiceTestCase;
use Litvinab\Bundle\CronEventBundle\Model\ScheduleModel;
use Litvinab\Bundle\CronEventBundle\Document\Schedule as ScheduleDocument;
use Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository;

/**
 * Class ScheduleModelTest
 *
 * @package Litvinab\Bundle\CronEventBundle\Tests\Model
 */
class ScheduleModelTest extends LitvinabServiceTestCase
{
    /**
     * Test get all schedules
     */
    public function testGetAllSchedules()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['findAll']);
        $this->setMockMethodsReturnValue($repository, ['findAll'], [$scheduleDocument]);

        $model = new ScheduleModel($repository);

        $result = $model->getAllSchedules();

        $this->assertCount(1, $result);
        $this->assertContains($scheduleDocument, $result);
    }

    /**
     * Test get schedule by ad id
     */
    public function testGetScheduleByAdId()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $queryBuilder = $this->getMockObject('Doctrine\MongoDB\Query\Query', ['getSingleResult']);
        $this->setMockMethodsReturnValue($queryBuilder, ['getSingleResult'], $scheduleDocument);

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['getSchedulesByAdId']);
        $this->setMockMethodsReturnValue($repository, ['getSchedulesByAdId'], $queryBuilder);

        $model = new ScheduleModel($repository);

        $result = $model->getSchedulesByAdId('test');

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test get all unexpired schedules
     */
    public function testUnexpiredSchedules()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['findByScheduleStatus']);
        $this->setMockMethodsReturnValue($repository, ['findByScheduleStatus'], [$scheduleDocument]);

        $model = new ScheduleModel($repository);

        $result = $model->getUnexpiredSchedules();

        $this->assertCount(1, $result);
        $this->assertContains($scheduleDocument, $result);
    }

    /**
     * Test get all unexpired schedules
     */
    public function testGetExpiredAndEnabledSchedules()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['getExpiredAndEnabledSchedules']);
        $this->setMockMethodsReturnValue($repository, ['getExpiredAndEnabledSchedules'], [$scheduleDocument]);

        $model = new ScheduleModel($repository);

        $result = $model->getExpiredAndEnabledSchedules();

        $this->assertCount(1, $result);
        $this->assertContains($scheduleDocument, $result);
    }

    /**
     * Test update schedule statuses
     */
    public function testUpdateStatuses()
    {
        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['updateStatus']);

        $model = new ScheduleModel($repository);

        $model->updateStatuses();

        $this->assertTrue(true);
    }

    /**
     * Test get save schedule
     */
    public function testSaveSchedules()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['save']);
        $this->setMockMethodsReturnValue($repository, ['save'], $scheduleDocument);

        $model = new ScheduleModel($repository);

        $result = $model->save($scheduleDocument);

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test get enable schedule
     */
    public function testEnableSchedule()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule', ['getStatus', 'setStatus']);
        $this->setMockMethodsReturnValue($scheduleDocument, ['getStatus'], false);

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['save']);
        $this->setMockMethodsReturnValue($repository, ['save'], $scheduleDocument);

        $model = new ScheduleModel($repository);

        $result = $model->enableSchedule($scheduleDocument);

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test disable schedule
     */
    public function testDisableSchedule()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule', ['getStatus', 'setStatus']);
        $this->setMockMethodsReturnValue($scheduleDocument, ['getStatus'], true);

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['save']);
        $this->setMockMethodsReturnValue($repository, ['save'], $scheduleDocument);

        $model = new ScheduleModel($repository);

        $result = $model->disableSchedule($scheduleDocument);

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test disable enabled schedule
     */
    public function testDisableEnabledSchedule()
    {
        $scheduleDocument = $this->getMockBuilder(ScheduleDocument::class)->setMethods(['getEnabled', 'setEnabled'])
            ->getMock();
        $scheduleDocument->expects($this->once())->method('getEnabled')->willReturn(true);
        $scheduleDocument->expects($this->once())->method('setEnabled')->with($this->equalTo(false));

        $repository = $this->getMockBuilder(ScheduleRepository::class)->disableOriginalConstructor()
            ->setMethods(['save'])
            ->getMock();
        $repository->expects($this->once())->method('save')
            ->with($this->equalTo($scheduleDocument))->willReturn($scheduleDocument);

        $model = new ScheduleModel($repository);

        $this->assertEquals($scheduleDocument, $model->disableSchedule($scheduleDocument));
    }

    /**
     * Test remove schedule
     */
    public function testRemoveSchedule()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['remove']);

        $model = new ScheduleModel($repository);

        $model->removeSchedule($scheduleDocument);

        $this->assertTrue(true);
    }

    /**
     * Test create schedule event
     */
    public function testCreateScheduleEvent()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['save']);
        $this->setMockMethodsReturnValue($repository, ['save'], $scheduleDocument);

        $model = new ScheduleModel($repository);

        $result = $model->createScheduleEvent('test', 'test', [], '20-04-2015');

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test create schedule timer
     */
    public function testCreateScheduleTimer()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['save']);
        $this->setMockMethodsReturnValue($repository, ['save'], $scheduleDocument);

        $model = new ScheduleModel($repository);

        $result = $model->createScheduleTimer('test', 'test', [], '20-05-2015');

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test update timers status
     */
    public function testUpdateTimersStatus()
    {
        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['updateTimersStatus', 'updateEventsStatus']);

        $model = new ScheduleModel($repository);

        $model->updateTimersStatus();

        $this->assertTrue(true);
    }

    /**
     * Test update events status
     */
    public function testUpdateEventStatus()
    {
        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['updateTimersStatus', 'updateEventsStatus']);

        $model = new ScheduleModel($repository);

        $model->updateEventsStatus();

        $this->assertTrue(true);
    }

    /**
     * Test update timers and events statuses
     */
    public function testUpdateAllTypeStatus()
    {
        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['updateTimersStatus', 'updateEventsStatus']);

        $model = new ScheduleModel($repository);

        $model->updateAllTypeStatus();

        $this->assertTrue(true);
    }

    /**
     * Test remove timer
     */
    public function testRemoveTimer()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['findOneBy', 'remove']);
        $this->setMockMethodsReturnValue($repository, ['findOneBy'], $scheduleDocument);

        $model = new ScheduleModel($repository);

        $model->removeTimer('test');

        $this->assertTrue(true);
    }

    /**
     * Test remove event
     */
    public function testRemoveEvent()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['findOneBy', 'remove']);
        $this->setMockMethodsReturnValue($repository, ['findOneBy'], $scheduleDocument);

        $model = new ScheduleModel($repository);

        $model->removeEvent('test');

        $this->assertTrue(true);
    }

    /**
     * Test get schedule by ad id
     */
    public function testFindScheduleByAdId()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $queryBuilder = $this->getMockObject('Doctrine\MongoDB\Query\Query', ['getSingleResult']);
        $this->setMockMethodsReturnValue($queryBuilder, ['getSingleResult'], $scheduleDocument);

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['findScheduleByAdId']);
        $this->setMockMethodsReturnValue($repository, ['findScheduleByAdId'], $queryBuilder);

        $model = new ScheduleModel($repository);

        $result = $model->findScheduleByAdId('test');

        $this->assertEquals($scheduleDocument, $result);
    }

    /**
     * Test remove schedule
     */
    public function testDeleteSchedule()
    {
        $scheduleDocument = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Document\Schedule');

        $repository = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository', ['remove']);

        $model = new ScheduleModel($repository);

        $model->deleteSchedule($scheduleDocument);

        $this->assertTrue(true);
    }

} 