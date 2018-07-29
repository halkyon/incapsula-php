<?php

namespace Incapsula\Command;

use Incapsula\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * @var Client;
     */
    protected $client;

    protected function configure()
    {
        $this->addOption('profile', 'p', InputOption::VALUE_OPTIONAL, 'Incapsula profile');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->client = new Client([
            'profile' => $input->getParameterOption(['--profile', '-p']),
        ]);
    }
}
