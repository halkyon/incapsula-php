<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class IntegrationIpsCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('integration:ips')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON')
            ->setDescription('List IP ranges')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $resp = $this->client->integration()->ips();
        $data = array_merge($resp['ipRanges'], $resp['ipv6Ranges']);

        if (true === $input->getOption('json')) {
            $output->write(json_encode($data));

            return 0;
        }

        $table = new Table($output);
        foreach ($data as $range) {
            $table->addRow([$range]);
        }
        $table->render();

        return 0;
    }
}
