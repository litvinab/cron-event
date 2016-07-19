<?php

namespace Litvinab\Bundle\CronEventBundle\Events;

use Symfony\Component\EventDispatcher\Event;
use Litvinab\Bundle\CronEventBundle\Document\Schedule;
use Litvinab\Bundle\CronEventBundle\Model\ScheduleModel;

/**
 * Class CronEvent
 *
 * @package Litvinab\Bundle\CronEventBundle\Events
 */
class CronEvent extends Event
{
    /**
     * @var Schedule
     */
    private $schedule;

    /**
     * @var ScheduleModel
     */
    private $model;

    /**
     * @param ScheduleModel $scheduleModel
     */
    public function __construct(ScheduleModel $scheduleModel)
    {
        $this->model = $scheduleModel;
    }

    /**
     * @param Schedule $schedule
     *
     * @return $this
     */
    public function setSchedule(Schedule $schedule)
    {
        $this->schedule = $schedule;

        return $this;
    }

    /**
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->schedule;
    }

    /**
     * Enable schedule
     *
     * @return void
     */
    public function enable()
    {
        $this->model->enableSchedule($this->schedule);
    }

    /**
     * disable schedule
     *
     * @return void
     */
    public function disable()
    {
        $this->model->disableSchedule($this->schedule);
    }

    /**
     * Remove schedule from db
     *
     * @return void
     */
    public function delete()
    {
        $this->model->removeSchedule($this->schedule);
    }
}
