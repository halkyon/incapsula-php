<?php

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

    /**
     * @var string
     */
    private $certificatePath;

    /**
     * @var string
     */
    private $privateKeyPath;

    protected function configure()
    {
        parent::configure();

        $this
            ->setName('StaticCacheMode:set')
            ->addArgument('site-id', InputArgument::REQUIRED, 'Site ID')
            ->setDescription('Ensure a site is in static caching header mode')
        ;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        parent::initialize($input, $output);

        $this->siteId = $input->getArgument('site-id');
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $return_val = $this->client->sites()->setStaticCacheMode(
            $this->siteId
        );

        return $return_val;
    }
}
