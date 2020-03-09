<?php

declare(strict_types=1);

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

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('account:login')
            ->addArgument('account-id', InputArgument::REQUIRED, 'account identifier')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->accountID = $input->getArgument('account-id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $token = $this->client->accounts()->getLoginToken($this->accountID);
        $output->write('https://my.incapsula.com/?token='.$token['generated_token'].PHP_EOL);

        return 0;
    }
}
