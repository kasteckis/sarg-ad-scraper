<?php


namespace App\Service;


use App\Entity\DailyVisit;
use App\Repository\DailyVisitRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class AnalyticsService
{
    private EntityManagerInterface $entityManager;

    /**
     * AnalyticsService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // User visit is logged only once per 24 hours per one unique user.
    public function logVisit(Request $request): void
    {
        /** @var DailyVisitRepository $dailyVisitRepo */
        $dailyVisitRepo = $this->entityManager->getRepository(DailyVisit::class);

        $realIp = $request->getClientIp();
        $anonymizedIp = sha1($realIp);

        $dailyVisit = $dailyVisitRepo->findOneBy([
            'hashedIp' => $anonymizedIp
        ]);

        if ($dailyVisit instanceof DailyVisit) {
            $now = new \DateTime();
            $diff = $now->diff($dailyVisit->getDate());

            if ($diff->days >= 1) {
                $dailyVisit->incrementCount();
                $dailyVisit->setRealIp($realIp);
                $dailyVisit->setDate(new \DateTime());
            }
        } else {
            $dailyVisit = new DailyVisit();
            $dailyVisit->setHashedIp($anonymizedIp);
            $dailyVisit->setRealIp($realIp);

            $this->entityManager->persist($dailyVisit);
        }

        $this->entityManager->flush();
    }
}
