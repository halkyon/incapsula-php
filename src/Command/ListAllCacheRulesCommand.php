<?php

namespace Incapsula\Command;

use function json_encode;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ListAllCacheRulesCommand extends SitesListCommand
{
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('sites:listcacherules')
            ->addOption('json', null, InputOption::VALUE_NONE, 'Output as JSON')
            ->setDescription('List all cache rules for all sites');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rules = $this->getRules();

        if (true === $input->getOption('json')) {
            $output->write(json_encode($rules));

            return 0;
        }

        $table = new Table($output);
        $table->setHeaders(['Site ID', 'Site Name', 'Rule ID', 'Action', 'Disabled?', 'Rule Name', 'Rule Filter', 'TTL']);

        foreach ($rules as $rule) {
            $table->addRow([
                $rule['site_id'],
                $rule['site_name'],
                $rule['rule_id'],
                $rule['action'],
                $rule['disabled'],
                $rule['name'],
                $rule['filter'],
                $rule['ttl'],
            ]);
        }

        $table->render();

        return 0;
    }

    private function getRules()
    {
        $api = $this->client->sites();
        $sites = $this->getSites();
        $rules = [];

        foreach ($sites as $site) {
            $page = 0;
            while (true) {
                $response = $api->listCacheRules($site['site_id'], 50, $page);

                if (empty($response)) {
                    break;
                }

                foreach ($response as $ruleType => $rulesOfType) {
                    foreach ($rulesOfType as $ruleData) {
                        $ttl = '';

                        if (isset($ruleData['ttl'], $ruleData['ttlUnit'])) {
                            $ttl = sprintf('%s %s', $ruleData['ttl'], $ruleData['ttlUnit']);
                        }

                        $rules[] = [
                            'site_id' => $site['site_id'],
                            'site_name' => $site['display_name'],
                            'rule_id' => $ruleData['id'],
                            'action' => $ruleType,
                            'disabled' => $ruleData['disabled'],
                            'name' => $ruleData['name'],
                            'filter' => $ruleData['filter'],
                            'ttl' => $ttl,
                        ];
                    }
                }

                ++$page;
            }
        }

        return $rules;
    }
}
