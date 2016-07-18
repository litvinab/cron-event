<?php


namespace Litvinab\CronEventBundle\Tests\EventListener;

use Litvinab\Api10Bundle\Tests\TestCase\LitvinabServiceTestCase;
use Litvinab\CronEventBundle\EventListener\CronListener;

/**
 * Class CronListenerTest
 *
 * @package Litvinab\CronEventBundle\Tests\EventListener
 */
class CronListenerTest extends LitvinabServiceTestCase
{
    /**
     * Test cron listener on event cron
     */
    public function testOnCron()
    {
        $cronManager = $this->getMockObject('Litvinab\CronEventBundle\Service\CronManager', ['runSchedules']);
        $event = $this->getMockObject('Symfony\Component\EventDispatcher\Event');

        $cronListener = new CronListener($cronManager);
        $cronListener->onCron($event);

        $this->assertTrue(true);
    }
} 