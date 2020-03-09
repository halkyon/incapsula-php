<?php

declare(strict_types=1);

namespace Incapsula\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SitesListCommand extends AbstractCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('sites:list')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON')
            ->setDescription('List all sites')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sites = $this->getSites();

        if (true === $input->getOption('json')) {
            $output->write(json_encode($sites));

            return 0;
        }

        $table = new Table($output);
        $table->setHeaders(['Site ID', 'Status', 'Domain']);
        foreach ($sites as $site) {
            $table->addRow([$site['site_id'], $site['status'], $site['domain']]);
        }
        $table->render();

        return 0;
    }

    protected function getSites(): array
    {
        $api = $this->client->sites();
        $sites = [];
        $page = 0;

        while (true) {
            $resp = $api->list(50, $page);
            if (empty($resp['sites'])) {
                break;
            }
            $sites = array_merge($sites, $resp['sites']);
            ++$page;
        }

        return $sites;
    }
}
