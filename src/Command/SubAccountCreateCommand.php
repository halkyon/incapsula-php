<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubAccountCreateCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $accountName;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('subaccount:create')
            ->addArgument('account-name', InputArgument::REQUIRED, 'sub account name')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->accountName = $input->getArgument('account-name');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client->Accounts()->add($this->accountName);
    }
}
