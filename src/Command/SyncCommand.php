<?php

namespace App\Command;

use App\Service\DownloadAdsService;
use App\Service\DownloadJobsTextsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SyncCommand extends Command
{
    protected static $defaultName = 'app:sync';

    private DownloadAdsService $downloadAdsService;

    private DownloadJobsTextsService $downloadJobsTextsService;

    /**
     * SyncCommand constructor.
     * @param DownloadAdsService $downloadAdsService
     * @param DownloadJobsTextsService $downloadJobsTextsService
     */
    public function __construct(DownloadAdsService $downloadAdsService, DownloadJobsTextsService $downloadJobsTextsService)
    {
        $this->downloadAdsService = $downloadAdsService;
        $this->downloadJobsTextsService = $downloadJobsTextsService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Sync data with database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->downloadAdsService->download();
        $this->downloadJobsTextsService->download();

        $io->success('Data was synced!');

        return Command::SUCCESS;
    }
}
