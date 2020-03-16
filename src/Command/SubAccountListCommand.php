<?php

declare(strict_types=1);

namespace Incapsula\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SubAccountListCommand extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('subaccount:list')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON')
            ->setDescription('List all sites')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $api = $this->client->accounts();
        $subAcc = [];
        $page = 0;

        while (true) {
            $resp = $api->list(50, $page);
            if (empty($resp['resultList'])) {
                break;
            }
            $subAcc = array_merge($subAcc, $resp['resultList']);
            ++$page;
        }

        if (true === $input->getOption('json')) {
            $output->write(json_encode($subAcc));

            return 0;
        }

        $table = new Table($output);
        $table->setHeaders(['Name', 'AccountID']);
        foreach ($subAcc as $acc) {
            $table->addRow([$acc['sub_account_name'], $acc['sub_account_id']]);
        }
        $table->render();

        return 0;
    }
}
