<?php

namespace Litvinab\Bundle\CronEventBundle\Repository;

use Doctrine\ODM\MongoDB\DocumentRepository;
use Litvinab\Bundle\CronEventBundle\Document\Schedule;
use Doctrine\MongoDB\Query\Query as QueryBuilder;

/**
 * Class ScheduleRepository
 *
 * @package Litvinab\Bundle\CronEventBundle\Repository
 */
class ScheduleRepository extends DocumentRepository
{
    /**
     * Update Timers status
     */
    public function updateTimersStatus()
    {
        $now = new \DateTime();

        $collection = $this->findBy(array('status' => 'unexpired', 'type' => 'timer'));

        foreach ($collection as $item) {

            if ($item->getEndTime() > $now) {
                continue;
            }

            $item->setEndTimeExpired(true);
            $item->setStatus('expired');
            $this->dm->persist($item);
            $this->dm->flush();
        }

    }

    /**
     * Update Events status
     */
    public function updateEventsStatus()
    {
        $now = new \DateTime();

        $collection = $this->findBy(array('status' => 'unexpired', 'type' => 'event'));

        foreach ($collection as $item) {

            if ($item->getStartTime() > $now) {
                continue;
            }

            $item->setStartTimeExpired(true);
            $item->setStatus('expired');
            $this->dm->persist($item);
            $this->dm->flush();
        }
    }

    /**
     * update status in tasks
     */
    public function updateStatus()
    {
        $now = new \DateTime();

        $collection = $this->findBy(array('status' => 'unexpired'));

        foreach ($collection as $item) {
            if ($item->getStartTime()) {
                if ($item->getStartTime() <= $now) {
                    $item->setStartTimeExpired(true);
                    $item->setStatus('expired');
                    $this->dm->persist($item);
                    $this->dm->flush();
                }
            }

            if ($item->getEndTime()) {
                if ($item->getEndTime() <= $now) {
                    $item->setEndTimeExpired(true);
                    $item->setStatus('expired');
                    $this->dm->persist($item);
                    $this->dm->flush();
                }
            }

        }
    }

    /**
     * Save
     *
     * @param Schedule $schedule
     *
     * @return Schedule
     */
    public function save(Schedule $schedule)
    {
        $this->dm->persist($schedule);
        $this->dm->flush();

        return $schedule;
    }

    /**
     * Remove
     *
     * @param Schedule $schedule
     */
    public function remove(Schedule $schedule)
    {
        $this->dm->remove($schedule);
        $this->dm->flush();
    }

    /**
     * Get expired and enabled schedules
     *
     * @return array
     */
    public function getExpiredAndEnabledSchedules()
    {
        return $this->findBy(array('status' => 'expired', 'enabled'=> true));
    }

    /**
     * Find by schedule status
     *
     * @param string $status
     *
     * @return Schedule[]
     */
    public function findByScheduleStatus($status)
    {
        return $this->findBy(
            array('scheduleStatus' => $status)
        );
    }

    /**
     * Find all schedules
     *
     * @return array
     */
    public function findAllSchedules()
    {
        return $this->findAll();
    }

    /**
     * Find schedule by ad id
     *
     * @param string $adId
     *
     * @return QueryBuilder
     */
    public function findScheduleByAdId($adId)
    {
        return $this->createQueryBuilder('LitvinabCronEventBundle:Schedule')
                    ->field('event')->equals('litvinab.cron-event.ad.not_published')
                    ->field('parameters.id')->equals($adId)
                    ->getQuery();
    }

    /**
     * Get schedules by ad id
     *
     * @param string $adId
     *
     * @return QueryBuilder
     */
    public function getSchedulesByAdId($adId)
    {
        return $this->createQueryBuilder('LitvinabCronEventBundle:Schedule')
                    ->field('event')
                    ->equals('ad.expired')
                    ->field('parameters.id')
                    ->equals($adId)
                    ->getQuery();
    }
}