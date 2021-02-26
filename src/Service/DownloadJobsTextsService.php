<?php

namespace App\Service;


use Doctrine\ORM\EntityManagerInterface;

class DownloadJobsTextsService
{
    private EntityManagerInterface $entityManager;

    /**
     * DownloadJobsTextsService constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function download(): void
    {
        // TODO!!!!!!
    }
}
