<?php

namespace Incapsula\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class PurgeSiteCommand extends AbstractCommand{
    /**
     * @var string
     */
    protected $siteId;
    /**
     * @var string
     */
    protected $resourcePattern;
    protected function configure()
    {
        parent::configure();

        $this
            ->setName('site:purge')
            ->setDescription('Purge URLs from a given site id')
            ->addArgument('site-id', InputArgument::REQUIRED, 'incapsula id of site to purge')
            ->addArgument('resource-pattern', InputArgument::OPTIONAL, 'string to match in the URL to be purged',"")
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
        $this->resourcePattern = $input->getArgument('resource-pattern');
        
    }
    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->client->sites()->purgeCache($this->siteId ,$this->resourcePattern);
        return 0;
    }
}