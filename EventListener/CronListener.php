<?php

namespace Litvinab\Bundle\CronEventBundle\EventListener;

use Symfony\Component\EventDispatcher\Event;
use Litvinab\Bundle\CronEventBundle\Service\CronManager;

/**
 * Class CronListener
 *
 * @package Litvinab\Bundle\CronEventBundle\EventListener
 */
class CronListener
{
    /**
     * Cron manager
     *
     * @var CronManager
     */
    protected $cronManager;

    /**
     * Initialize cron manager
     *
     * @param CronManager $cronManager
     */
    public function __construct(CronManager $cronManager)
    {
        $this->cronManager = $cronManager;
    }

    /**
     * On cron event
     *
     * @param Event $event
     */
    public function onCron(Event $event)
    {
        $this->cronManager->runSchedules();
    }

}
