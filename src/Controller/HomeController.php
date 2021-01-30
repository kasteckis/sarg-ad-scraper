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
        $ads = $this->getDoctrine()->getRepository(Ad::class)->findBy([], ['datetime' => 'desc']);

        return $this->render('home/index.html.twig', [
            'ads' => $ads
        ]);
    }
}
