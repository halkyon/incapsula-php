<?php

declare(strict_types=1);

namespace Incapsula\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetStaticCacheModeCommand extends AbstractCommand
{
    /**
     * @var string
     */
    private $siteId;

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('staticcachemode:set')
            ->addArgument('site-id', InputArgument::REQUIRED, 'Site ID')
            ->setDescription('Ensure a site is in static caching header mode')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->siteId = $input->getArgument('site-id');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->client->sites()->setStaticCacheMode(
            $this->siteId,
        );

        return 0;
    }
}
