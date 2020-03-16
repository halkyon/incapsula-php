<?php

declare(strict_types=1);

namespace Incapsula\Command;

use function json_encode;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SetAllCacheModeCommand extends SitesListCommand
{
    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('sites:setcachemode')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON')
            ->setDescription('Set all sites to static caching header mode')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $changes = $this->setCacheMode();

        if (true === $input->getOption('json')) {
            $output->write(json_encode($changes));

            return 0;
        }

        $output->writeln('Sites that were changed in this run');
        $table = new Table($output);
        $table->setHeaders(['Site ID', 'Site Name', 'Return Message']);

        foreach ($changes as $changed) {
            $table->addRow([
                $changed['site_id'],
                $changed['domain'],
                $changed['return'],
            ]);
        }

        $table->render();

        return 0;
    }

    private function setCacheMode(): array
    {
        $api = $this->client->sites();
        $sites = $this->getSites();
        $changed = [];
        foreach ($sites as $site) {
            $returnVal = $this->client->sites()->setStaticCacheMode($site['site_id']);
            $mode = $site['acceleration_level'];
            if ('advanced' === $mode) {
                array_push($changed, [
                    'site_id' => $site['site_id'],
                    'domain' => $site['domain'],
                    'return' => $returnVal['res_message'],
                ]);
            }
        }

        return $changed;
    }
}
