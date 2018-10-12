<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AccountLoginTokenCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $accountID;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('account:login')
            ->addArgument('account-id', InputArgument::REQUIRED, 'account identifier')
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
        $token =$this->client->Accounts()->getLoginToken($this->accountID);
        $output->write('https://my.incapsula.com/?token='.$token['generated_token'].PHP_EOL);
    }
}
