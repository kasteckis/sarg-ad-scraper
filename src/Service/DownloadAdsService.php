<?php
namespace App\Service;


use App\Entity\Ad;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class DownloadAdsService
{
    private EntityManagerInterface $entityManager;

    /**
     * DownloadAdsService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function download(): void
    {
        $client = new Client(HttpClient::create(['timeout' => 60]));
        $crawler = $client->request('GET', 'https://sarg.lt/index.php?/cp/5-skelbimai/');


        $crawler->filter('article > div')->each(function ($node) {
            $ads = explode('<br>', $node->html());
            $ads = array_reverse($ads);
            foreach ($ads as $ad) {
                if ($ad) {
                    $this->generateAd($ad);
                }
            }
        });
    }

    private function generateAd(string $adText): void
    {
        $adText = $this->removeHtmlElements($adText);

        $date = $this->getDate($adText);
        $reporterName = $this->getReporterName($adText);
        $text = $this->getText($adText);

        if ($date && $reporterName && $text) {
            $this->createAdIfNeeded($date, $reporterName, $text);
        }
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

        return new DateTime($dateString);
    }

    private function getReporterName(string $adText): ?string
    {
        $reporterNameStart = strpos($adText, ']');
        $reporterNameEnd = strpos($adText, ':', $reporterNameStart);

        $reporterName = null;

        for ($i = $reporterNameStart+2; $i < $reporterNameEnd; $i++) {
            $reporterName = $reporterName . $adText[$i];
        }

        return $reporterName;
    }

    private function getText(string $adText): ?string
    {
        $reporterNameStart = strpos($adText, ']');
        $reporterNameEnd = strpos($adText, ':', $reporterNameStart);

        return substr($adText, $reporterNameEnd+2);
    }

    private function createAdIfNeeded(\DateTime $dateTime, string $reporterName, string $text): void
    {
        $ad = $this->entityManager->getRepository(Ad::class)->findOneBy([
            'text' => $text
        ]);

        if ($ad) {
            $ad->setDatetime($dateTime);
            $ad->setReporter($reporterName);
        } else {
            $ad = new Ad();
            $ad->setDatetime($dateTime);
            $ad->setReporter($reporterName);
            $ad->setText($text);

            $this->entityManager->persist($ad);
        }

        $this->entityManager->flush();
    }
}
