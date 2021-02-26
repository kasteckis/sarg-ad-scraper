<?php

namespace App\Controller;

use App\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        $adsCount = $this->getDoctrine()->getRepository(Ad::class)->count([]);

        return $this->render('home/index.html.twig', [
            'adsCount' => $adsCount
        ]);
    }

    /**
     * @Route("/skelbimai/old", name="ads_client_side_pagination")
     */
    public function skelbimaiOld(): Response
    {
        $ads = $this->getDoctrine()->getRepository(Ad::class)->findBy([], ['datetime' => 'desc']);

        return $this->render('home/clientSidePaginationIndex.html.twig', [
            'ads' => $ads
        ]);
    }

    /**
     * @Route("/numatomos", name="coming_job_list")
     */
    public function comingJobList(): Response
    {
        $ads = $this->getDoctrine()->getRepository(Ad::class)->findBy([], ['datetime' => 'desc']);

        return $this->render('home/jobs.html.twig', [
            'ads' => $ads
        ]);
    }
}
