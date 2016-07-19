<?php


namespace Litvinab\Bundle\CronEventBundle\Tests\Repository;

use Litvinab\UserBundle\Tests\TestCase\RepositoryTestCase;
use Litvinab\Bundle\CronEventBundle\Document\Schedule;

/**
 * Class ScheduleRepositoryTest
 *
 * @package Litvinab\Bundle\CronEventBundle\Tests\Repository
 */
class ScheduleRepositoryTest extends RepositoryTestCase
{
    /**
     * User Repository
     *
     * @var ScheduleRepository
     */
    private $repository;

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        /** @var \Doctrine\ODM\MongoDB\SchemaManager $schemaManager */
        $schemaManager = $this->dm->getSchemaManager();

        $schemaManager->dropDocumentCollection('CronEventBundle:Schedule');

        $this->repository = $this->dm->getRepository('CronEventBundle:Schedule');

        $now = new \DateTime();
        $yesterday = $now->modify('-1 day'); // all my troubles seemed so far away
                                             // Now it looks as though they're here to stay
                                            //  oh, I believe in yesterday

        $tomorrow = $now->modify('+1 day');

        $schedule = new Schedule();
        $schedule->setName('testEvent');
        $schedule->setEnabled(true);
        $schedule->setStartTime($tomorrow);
        $schedule->setStartTimeExpired(false);
        $schedule->setStatus('unexpired');
        $schedule->setType('event');
        $schedule->setEvent('cron_event.ad.not_published');
        $schedule->setParameters(['id' => 'test']);

        $this->dm->persist($schedule);
        $this->dm->flush();

        $schedule = new Schedule();
        $schedule->setName('testTimer');
        $schedule->setEnabled(true);
        $schedule->setEndTime($yesterday);
        $schedule->setEndTimeExpired(false);
        $schedule->setStatus('unexpired');
        $schedule->setType('timer');
        $schedule->setParameters(['id' => 'test2']);

        $this->dm->persist($schedule);
        $this->dm->flush();
    }

    /**
     * Test updates timer status
     */
    public function testUpdateTimersStatus()
    {
        $this->repository->updateTimersStatus();

        $result = $this->repository->findOneBy(['name' => 'testTimer']);

        $this->assertEquals('testTimer', $result->getName());
        $this->assertTrue($result->getEndTimeExpired());
    }

    /**
     * Test update all statuses
     */
    public function testUpdateStatuses()
    {
        $this->repository->updateTimersStatus();
        $this->repository->updateStatus();

        $result = $this->repository->findOneBy(['name' => 'testTimer']);

        $this->assertEquals('testTimer', $result->getName());
        $this->assertEquals('expired', $result->getStatus());
        $this->assertTrue($result->getEndTimeExpired());
    }

    /**
     * Test save schedule
     */
    public function testSave()
    {
        $schedule = new Schedule();
        $schedule->setName('testEvent');
        $schedule->setEnabled(true);
        $schedule->setStartTime(new \DateTime());
        $schedule->setStartTimeExpired(false);
        $schedule->setStatus('unexpired');
        $schedule->setType('event');

        $result = $this->repository->save($schedule);

        $this->assertEquals($schedule->getName(), $result->getName());
        $this->assertEquals($schedule->getEnabled(), $result->getEnabled());
        $this->assertEquals($schedule->getStartTime(), $result->getStartTime());
        $this->assertEquals($schedule->getStartTimeExpired(), $result->getStartTimeExpired());
        $this->assertEquals($schedule->getStatus(), $result->getStatus());
        $this->assertEquals($schedule->getType(), $result->getType());
    }

    /**
     * Test remove schedule
     */
    public function testRemove()
    {
        $schedule = new Schedule();
        $schedule->setName('testEvent2');
        $schedule->setEnabled(true);
        $schedule->setStartTime(new \DateTime());
        $schedule->setStartTimeExpired(false);
        $schedule->setStatus('unexpired');
        $schedule->setType('event');

        $this->repository->remove($schedule);

        $result = $this->repository->findOneBy(['name' => 'testEvent2']);

        $this->assertNull($result);
    }

    /**
     * Test get expired and enabled status schedule
     */
    public function testGetExpiredAndEnabledSchedules()
    {
        $this->repository->updateTimersStatus();

        $result = $this->repository->getExpiredAndEnabledSchedules();

        $this->assertCount(1, $result);
        $this->assertTrue($result[0]->getEnabled());
        $this->assertEquals('expired', $result[0]->getStatus());
    }

    /**
     * Test fin schedule by status
     */
    public function testFindByScheduleStatus()
    {
        $result = $this->repository->findByScheduleStatus('expired');

        $this->assertCount(0, $result);
    }

    /**
     * Test find all schedules
     */
    public function testFindAllSchedules()
    {
        $result = $this->repository->findAllSchedules();

        $this->assertCount(2, $result);
    }

    /**
     *  Test find schedules by ad id
     */
    public function testFindScheduleByAdId()
    {
        $result = $this->repository->findScheduleByAdId('test')->getSingleResult();

        $this->assertCount(1, $result->getParameters());
        $this->assertContains('test', $result->getParameters());
    }

    /**
     * Test find schedule by ad id event ad expired
     */
    public function testFindScheduleByAdIdEventAdExpired()
    {
        $schedule = new Schedule();
        $schedule->setName('testEvent');
        $schedule->setEnabled(true);
        $schedule->setStartTime(new \DateTime());
        $schedule->setStartTimeExpired(false);
        $schedule->setStatus('unexpired');
        $schedule->setType('event');
        $schedule->setEvent('ad.expired');
        $schedule->setParameters(['id' => 'test']);

        $this->dm->persist($schedule);
        $this->dm->flush();

        $result = $this->repository->getSchedulesByAdId('test')->getSingleResult();

        $this->assertCount(1, $result->getParameters());
        $this->assertContains('test', $result->getParameters());
    }

} 