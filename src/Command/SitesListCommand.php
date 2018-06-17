<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\Inputinterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SitesListCommand extends AbstractCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('sites:list')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON')
            ->setDescription('List all sites')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = [];
        $pageNum = 0;

        while (true) {
            $resp = $this->client->sites()->list(50, $pageNum);
            if (empty($resp['sites'])) {
                break;
            }
            $data = array_merge($data, $resp['sites']);
            ++$pageNum;
        }

        $sites = array_map(function ($site) {
            return [
                'site_id' => $site['site_id'],
                'status' => $site['status'],
                'domain' => $site['domain'],
            ];
        }, $data);

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
}
