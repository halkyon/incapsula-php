<?php

namespace Incapsula\Command;

use Incapsula\Client;
use Incapsula\Credentials\CredentialProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\Inputinterface;
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
        $this->addOption('profile', 'p', InputOption::VALUE_OPTIONAL, 'Incapsula profile', 'default');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $credentials = CredentialProvider::env();
        if (null === $credentials) {
            $credentials = CredentialProvider::ini($input->getParameterOption(['--profile', '-p']));
        }

        $this->client = new Client($credentials->getApiId(), $credentials->getApiKey());
    }
}
