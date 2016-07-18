<?php
namespace Litvinab\CronEventBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * CronTask document
 *
 * @MongoDB\Document(repositoryClass="Litvinab\CronEventBundle\Repository\ScheduleRepository")
 */
class Schedule
{
    /**
     * @MongoDB\Id(strategy="auto", name="id")
     */
    protected $id;

    /**
     * @MongoDB\String(name="name")
     */
    protected $name;

    /**
     * @MongoDB\String(name="type")
     */
    protected $type;

    /**
     * @MongoDB\String(name="event")
     */
    protected $event;

    /**
     * @MongoDB\Hash(name="parameters")
     */
    protected $parameters = array();

    /**
     * @MongoDB\Boolean(name="enabled")
     */
    protected $enabled;

    /**
     * @MongoDB\String(name="status")
     */
    protected $status;

    /**
     * @MongoDB\Date(name="start_time")
     */
    protected $startTime;

    /**
     * @MongoDB\Date(name="end_time")
     */
    protected $endTime;

    /**
     * @MongoDB\Boolean(name="start_time_expired")
     */
    protected $startTimeExpired;

    /**
     * @MongoDB\Boolean(name="end_time_expired")
     */
    protected $endTimeExpired;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $event
     *
     * @return $this
     */
    public function setEvent($event)
    {
        $this->event = $event;

        return $this;
    }

    /**
     * @return string
     */
    public function getEvent()
    {
        return $this->event;
    }

    /**
     * @param array $parameters
     *
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param bool $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled)
    {
        $this->enabled = (bool) $enabled;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param string $status
     *
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $startTime
     *
     * @return $this
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * @param string $endTime
     *
     * @return $this
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * @param mixed $startTimeExpired
     *
     * @return $this
     */
    public function setStartTimeExpired($startTimeExpired)
    {
        $this->startTimeExpired = (bool) $startTimeExpired;

        return $this;
    }

    /**
     * @return bool
     */
    public function getStartTimeExpired()
    {
        return $this->startTimeExpired;
    }

    /**
     * @param mixed $endTimeExpired
     *
     * @return $this
     */
    public function setEndTimeExpired($endTimeExpired)
    {
        $this->endTimeExpired = (bool) $endTimeExpired;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEndTimeExpired()
    {
        return $this->endTimeExpired;
    }
}
