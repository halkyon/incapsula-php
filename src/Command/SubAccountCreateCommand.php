<?php

declare(strict_types=1);

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

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('subaccount:create')
            ->addArgument('account-name', InputArgument::REQUIRED, 'sub account name')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->accountName = $input->getArgument('account-name');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->client->accounts()->add($this->accountName);

        return 0;
    }
}
