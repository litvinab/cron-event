<?php


namespace Litvinab\Bundle\CronEventBundle\Tests\EventListener;

use Litvinab\Api10Bundle\Tests\TestCase\LitvinabServiceTestCase;
use Litvinab\Bundle\CronEventBundle\EventListener\CronListener;

/**
 * Class CronListenerTest
 *
 * @package Litvinab\Bundle\CronEventBundle\Tests\EventListener
 */
class CronListenerTest extends LitvinabServiceTestCase
{
    /**
     * Test cron listener on event cron
     */
    public function testOnCron()
    {
        $cronManager = $this->getMockObject('Litvinab\Bundle\CronEventBundle\Service\CronManager', ['runSchedules']);
        $event = $this->getMockObject('Symfony\Component\EventDispatcher\Event');

        $cronListener = new CronListener($cronManager);
        $cronListener->onCron($event);

        $this->assertTrue(true);
    }
} 