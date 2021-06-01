<?php

namespace App\Repository;

use App\Entity\DailyVisit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DailyVisit|null find($id, $lockMode = null, $lockVersion = null)
 * @method DailyVisit|null findOneBy(array $criteria, array $orderBy = null)
 * @method DailyVisit[]    findAll()
 * @method DailyVisit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DailyVisitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DailyVisit::class);
    }
}
