<?php

namespace Litvinab\Bundle\CronEventBundle\Service;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Litvinab\Bundle\CronEventBundle\Document\Schedule;
use Litvinab\Bundle\CronEventBundle\Model\ScheduleModel;
use Litvinab\Bundle\CronEventBundle\Events\CronEvent;

/**
 * Class CronManager
 *
 * @package Litvinab\Bundle\CronEventBundle\Service
 */
class CronManager
{
    /**
     * @var ScheduleModel
     */
    protected $model;

    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @param EventDispatcherInterface $dispatcher
     */
    public function setDispatcher(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }

    /**
     * @param ScheduleModel $scheduleModel
     */
    public function setScheduleModel(ScheduleModel $scheduleModel)
    {
        $this->model = $scheduleModel;
    }

    /**
     * Run schedules
     *
     * @return void
     */
    public function runSchedules()
    {
        $this->model->updateAllTypeStatus();

        $schedules = $this->model->getExpiredAndEnabledSchedules();

        foreach ($schedules as $schedule) {

            $event = new CronEvent($this->model);

            $event->setSchedule($schedule);

            $this->dispatcher->dispatch('cron_event.'.$schedule->getEvent(), $event);
        }
    }

    /**
     * Get all schedules
     *
     * @return Schedule[]
     */
    public function getSchedules()
    {
        return $this->model->getAllSchedules();
    }

    /**
     * Get schedule by id
     *
     * @param string $adId
     *
     * @return null|Schedule[]
     */
    public function getSchedulesByAdId($adId)
    {
        return $this->model->getSchedulesByAdId($adId);
    }

    /**
     * @param Schedule $schedule
     *
     * @return Schedule
     */
    public function updateSchedule(Schedule $schedule)
    {
        return $this->model->save($schedule);
    }

    /**
     * @param string $name
     * @param string $event
     * @param string $seconds
     * @param array  $parameters
     * @param bool   $enabled
     *
     * @return Schedule
     */
    public function setTimer($name, $event, $seconds, $parameters = array(),  $enabled = true)
    {
        $date = new \DateTime();
        $date->modify('+' . $seconds. ' seconds');
        $endTime = $date->format('Y-m-d H:i:s');

        return $this->model->createScheduleTimer($name, $event, $parameters, $endTime, $enabled);
    }

    /**
     * @param string    $name
     * @param string    $event
     * @param string    $startTime
     * @param array     $parameters
     * @param bool      $enabled
     *
     * @return Schedule
     */
    public function setEvent($name, $event, $startTime, $parameters = array(),  $enabled = true)
    {
        return $this->model->createScheduleEvent($name, $event, $parameters, $startTime, $enabled);
    }

    /**
     * Remove timer from db
     *
     * @param string $name
     *
     * @return void
     */
    public function deleteTimer($name)
    {
        $this->model->removeTimer($name);
    }

    /**
     * Remove event from db
     *
     * @param string $name
     *
     * @return void
     */
    public function deleteEvent($name)
    {
        $this->model->removeEvent($name);
    }

    /**
     * @param string $adId
     *
     * @return Schedule|null
     */
    public function findScheduleByAdId($adId)
    {
       return $this->model->findScheduleByAdId($adId);
    }

    /**
     * @param Schedule $schedule
     *
     * @return bool
     */
    public function deleteSchedule(Schedule $schedule)
    {
        $this->model->removeSchedule($schedule);

        return true;
    }
}
