<?php

declare(strict_types=1);

namespace Incapsula\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeSiteCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $siteId;
    /**
     * @var string
     */
    protected $resourcePattern;

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('site:purge')
            ->setDescription('Purge URLs from a given site id')
            ->addArgument('site-id', InputArgument::REQUIRED, 'incapsula id of site to purge')
            ->addArgument('resource-pattern', InputArgument::OPTIONAL, 'string to match in the URL to be purged', '')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->siteId = $input->getArgument('site-id');
        $this->resourcePattern = $input->getArgument('resource-pattern');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->client->sites()->purgeCache($this->siteId, $this->resourcePattern);

        return 0;
    }
}
