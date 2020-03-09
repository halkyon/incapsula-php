<?php

declare(strict_types=1);

namespace Incapsula\Command;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CustomCertificateUploadCommand extends AbstractCommand
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

    protected function configure(): void
    {
        parent::configure();

        $this
            ->setName('customcertificate:upload')
            ->addArgument('site-id', InputArgument::REQUIRED, 'Site ID')
            ->addArgument('certificate-path', InputArgument::REQUIRED, 'Certificate path')
            ->addArgument('private-key-path', InputArgument::REQUIRED, 'Private key path')
            ->setDescription('Upload a custom certificate to a site')
        ;
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);

        $this->siteId = $input->getArgument('site-id');
        $this->certificatePath = $input->getArgument('certificate-path');
        $this->privateKeyPath = $input->getArgument('private-key-path');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->client->sites()->uploadCustomCertificate(
            $this->siteId,
            file_get_contents($this->certificatePath),
            file_get_contents($this->privateKeyPath)
        );

        return 0;
    }
}
