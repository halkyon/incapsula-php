<?php

declare(strict_types=1);

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

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('subaccount:delete')
            ->addArgument('account-id', InputArgument::REQUIRED, 'sub account identifier')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->accountID = $input->getArgument('account-id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->client->accounts()->delete($this->accountID);

        return 0;
    }
}
