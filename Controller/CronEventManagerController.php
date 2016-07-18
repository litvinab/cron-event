<?php

namespace Litvinab\CronEventBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Litvinab\CronEventBundle\Document\CronTask;
use Symfony\Component\HttpFoundation\Response;
use Litvinab\CronEventBundle\Repository\CronScheduleRepository;

/**
 * Contrtoller for test cron action
 *
 * Class CronEventManagerController
 *
 * @package Litvinab\CronEventBundle\Controller
 */
class CronEventManagerController extends Controller
{
    /**
     * @return Response
     */
    public function indexAction()
    {
        $cronManager = $this->get('litvinab.cron-event.manager');

        $timer = $cronManager->setTimer('My timer', 'test_timer', 3600);
        $event = $cronManager->setEvent('My event', 'test_event', '01-01-2015');

        return new Response($timer->getName().' '.$event->getName());
    }

    /**
     * @return Response
     */
    public function showAction()
    {
        $cronManager = $this->get('litvinab.cron-event.manager');

        $schedules = $cronManager->getSchedules();

        var_dump($schedules);

        return new Response('finish');
    }

    /**
     * @return Response
     */
    public function runAction()
    {
        $cronManager = $this->get('litvinab.cron-event.manager');

        $cronManager->runSchedules();

        return new Response('Schedules Run');
    }

    /**
     * @return Response
     */
    public function removeAction()
    {
        $cronManager = $this->get('litvinab.cron-event.manager');
        $cronManager->deleteTimer('My timer');
        $cronManager->deleteTimer('My event');

        return new Response('Timer && event deleted');
    }
}
