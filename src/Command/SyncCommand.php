<?php

namespace App\Command;

use App\Entity\Ad;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpClient\HttpClient;

class SyncCommand extends Command
{
    protected static $defaultName = 'app:sync';

    private EntityManagerInterface $entityManager;

    /**
     * SyncCommand constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
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

        $client = new Client(HttpClient::create(['timeout' => 60]));
        $crawler = $client->request('GET', 'https://sarg.lt/index.php?/cp/5-skelbimai/');


        $crawler->filter('article > div')->each(function ($node) {
            $ads = explode('<br>', $node->html());
            foreach ($ads as $ad) {
                $this->generateAd($ad);
            }
        });

        $io->success('Ads were synced!');

        return Command::SUCCESS;
    }

    private function generateAd(string $adText): void
    {
        $adText = $this->removeHtmlElements($adText);

        $date = $this->getDate($adText);

        $a = null;
    }

    private function removeHtmlElements(string $adText): string
    {
        $htmlElementsToRemove = [
            '<b>',
            '</b>'
        ];

        foreach ($htmlElementsToRemove as $htmlElement) {
            $adText = str_replace($htmlElement, '', $adText);
        }

        return $adText;
    }

    private function getDate(string $adText): \DateTime
    {
        $dateStart = strpos($adText, '[');
        $dateEnd = strpos($adText, ']');

        $dateString = null;

        for ($i = $dateStart+1; $i < $dateEnd; $i++) {
            $dateString = $dateString . $adText[$i];
        }

        $a = new DateTimeImmutable();
        $b = new DateTimeImmutable('2020-01-01');

        var_dump($b);

        return new DateTime();
    }
}
