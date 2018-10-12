<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SubAccountDeleteCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $accountID;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('subaccount:delete')
            ->addArgument('account-id', InputArgument::REQUIRED, 'sub account identifier')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->accountID = $input->getArgument('account-id');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client->Accounts()->delete($this->accountID);
    }
}
