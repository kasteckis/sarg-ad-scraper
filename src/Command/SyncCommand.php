<?php

namespace App\Command;

use App\Service\DownloadAdsService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;

class SyncCommand extends Command
{
    protected static $defaultName = 'app:sync';

    private DownloadAdsService $downloadAdsService;

    /**
     * SyncCommand constructor.
     * @param DownloadAdsService $downloadAdsService
     */
    public function __construct(DownloadAdsService $downloadAdsService)
    {
        $this->downloadAdsService = $downloadAdsService;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Sync ads with database')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->downloadAdsService->download();

        $io->success('Ads were synced!');

        return Command::SUCCESS;
    }
}
