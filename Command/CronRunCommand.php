<?php

namespace Litvinab\Bundle\CronEventBundle\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class CronRunCommand
 *
 * @package Litvinab\Bundle\CronEventBundle\Command
 */
class CronRunCommand extends ContainerAwareCommand
{
    /**
     * config crone
     */
    protected function configure()
    {
        $this->setName('cron:run')
             ->setDescription('Run tasks of CRON!');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $logger = $this->getContainer()->get('litvinab.cron-event.logger');

        $logger->addInfo('--------------------------- CRON BEGIN ---------------------------');

        $dispatcher = $this->getContainer()->get('event_dispatcher');
        $dispatcher->dispatch('cron.run', new Event());

        $logger->addInfo('--------------------------- CRON END ---------------------------');
    }
}