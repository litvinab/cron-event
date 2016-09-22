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
class CronListCommand extends ContainerAwareCommand
{
    /**
     * config crone
     */
    protected function configure()
    {
        $this->setName('cron:list')
            ->setDescription('List all schedules');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cronManager = $this->getContainer()->get('cron_event.manager');
        $schedules = $cronManager->getSchedules();

        $headers = array(
            'Name',
            'Parameters',
            'Type',
            'Event',
            'Enabled',
            'Status',
            'Start Time',
            'End Time'
        );

        $rows = array();

        foreach($schedules AS $s) {
            $startDate  = !is_null($s->getStartTime()) ? $s->getStartTime()->date : '';
            $endDate    = !is_null($s->getEndTime()) ? $s->getEndTime()->format('Y-m-d H:i:s') : '';
            $eventName  = 'cron_event.'.$s->getEvent();
            $params     = print_r($s->getParameters(), true);

            $rows[] = array(
                $s->getName(),
                $params,
                $s->getType(),
                $eventName,
                $s->getEnabled(),
                $s->getStatus(),
                $startDate,
                $endDate
            );
        }

        $table = $this->getHelper('table');
        $table
            ->setHeaders($headers)
            ->setRows($rows)
        ;

        $table->render($output);
    }
}