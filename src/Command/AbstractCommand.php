<?php

namespace Incapsula\Command;

use Incapsula\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Inputinterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractCommand extends Command
{
    /**
     * @var Incapsula\Client;
     */
    protected $client;

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->client = new Client('foo', 'foo');
    }
}
