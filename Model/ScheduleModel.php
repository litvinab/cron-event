<?php
namespace Litvinab\Bundle\CronEventBundle\Model;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Litvinab\Bundle\CronEventBundle\Repository\ScheduleRepository;
use Litvinab\Bundle\CronEventBundle\Document\Schedule;

/**
 * Class CronTaskModel
 *
 * @package Litvinab\Bundle\CronEventBundle\Model
 */
class ScheduleModel
{
    /**
     * Instance of DocumentRepository
     *
     * @var DocumentRepository
     */
    protected $repository;

    /**
     * Initializes a ScheduleRepository
     *
     * @param ScheduleRepository $repository
     */
    public function __construct(ScheduleRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return array
     */
    public function getAllSchedules()
    {
        return $this->repository->findAll();
    }

    /**
     * @param string $adId
     *
     * @return Schedule|null
     */
    public function getSchedulesByAdId($adId)
    {
        return $this->repository->getSchedulesByAdId($adId)->getSingleResult();
    }

    /**
     * @return Schedule[]
     */
    public function getUnexpiredSchedules()
    {
        $schedules = $this->repository->findByScheduleStatus('unexpired');

        return $schedules;
    }

    /**
     * @return Schedule[]
     */
    public function getExpiredAndEnabledSchedules()
    {
        $schedules = $this->repository->getExpiredAndEnabledSchedules();

        return $schedules;
    }

    /**
     * update statuses in schedules where status unexpired
     */
    public function updateStatuses()
    {
        $this->repository->updateStatus();
    }

    /**
     * @param Schedule $schedule
     *
     * @return Schedule
     */
    public function save(Schedule $schedule)
    {
        return $this->repository->save($schedule);
    }

    /**
     * @param Schedule $schedule
     *
     * @return Schedule
     */
    public function enableSchedule(Schedule $schedule)
    {
        if (!$schedule->getEnabled()) {
            $schedule->setEnabled(true);
            $schedule = $this->save($schedule);
        }

        return $schedule;
    }

    /**
     * @param Schedule $schedule
     *
     * @return Schedule
     */
    public function disableSchedule(Schedule $schedule)
    {
        if ($schedule->getEnabled()) {
            $schedule->setEnabled(false);
            $schedule = $this->save($schedule);
        }

        return $schedule;
    }

    /**
     * @param Schedule $schedule
     */
    public function removeSchedule(Schedule $schedule)
    {
        $this->repository->remove($schedule);
    }

    /**
     * @param string $name
     * @param string $event
     * @param array  $parameters
     * @param string $startTime
     * @param bool   $enabled
     *
     * @return Schedule
     */
    public function createScheduleEvent($name, $event, array $parameters, $startTime, $enabled = true)
    {
        $document = new Schedule();

        $document->setName($name);
        $document->setType('event');
        $document->setEvent($event);
        $document->setParameters($parameters);
        $document->setEnabled($enabled);
        $document->setStatus('unexpired');
        $document->setStartTime($startTime);

        $document->setEndTime(null);
        $document->setStartTimeExpired(false);
        $document->setEndTimeExpired(false);

        return $this->save($document);
    }

    /**
     * @param string $name
     * @param string $event
     * @param array  $parameters
     * @param string $endTime
     * @param bool   $enabled
     *
     * @return Schedule
     */
    public function createScheduleTimer($name, $event, array $parameters, $endTime, $enabled = true)
    {
        $document = new Schedule();

        $document->setName($name);
        $document->setType('timer');
        $document->setEvent($event);
        $document->setParameters($parameters);
        $document->setEnabled($enabled);
        $document->setStatus('unexpired');
        $document->setStartTime(null);
        $document->setEndTime($endTime);
        $document->setStartTimeExpired(false);
        $document->setEndTimeExpired(false);

        return $this->save($document);
    }

    /**
     * Update Timers Status
     *
     * @return void
     */
    public function updateTimersStatus()
    {
        $this->repository->updateTimersStatus();
    }

    /**
     * Update events status
     *
     * @return void
     */
    public function updateEventsStatus()
    {
        $this->repository->updateEventsStatus();
    }

    /**
     * Update timers and events statuses
     *
     * @return void
     */
    public function updateAllTypeStatus()
    {
        $this->repository->updateEventsStatus();
        $this->repository->updateTimersStatus();
    }

    /**
     * Remove timer from db
     *
     * @param string $timerName
     *
     * @return void
     */
    public function removeTimer($timerName)
    {
        if ($timer = $this->repository->findOneBy(array('name' => $timerName, 'type' => 'timer'))) {
            $this->repository->remove($timer);
        }
    }

    /**
     * @param string $eventName
     *
     * @return void
     */
    public function removeEvent($eventName)
    {
        if ($event = $this->repository->findOneBy(array('name' => $eventName, 'type' => 'event'))) {
            $this->repository->remove($event);
        }
    }

    /**
     * @param string $adId
     *
     * @return Schedule|null
     */
    public function findScheduleByAdId($adId)
    {
        return $this->repository->findScheduleByAdId($adId)->getSingleResult();
    }

    /**
     * @param Schedule $schedule
     */
    public function deleteSchedule(Schedule $schedule)
    {
        $this->repository->remove($schedule);
    }

}