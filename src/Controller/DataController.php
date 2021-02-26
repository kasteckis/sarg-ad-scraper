<?php

namespace App\Controller;

use App\Entity\Ad;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DataController extends AbstractController
{
    /**
     * @Route("/api/skelbimai", name="ads_data")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $offset = $request->get('offset') ? $request->get('offset') : 0;
        $limit = $request->get('limit') ? $request->get('limit') : 100;
        $search = $request->get('search') ? $request->get('search') : '';

        $ads = $this->getDoctrine()->getRepository(Ad::class)->getAds($offset, $limit, $search);
        $adsCount = $this->getDoctrine()->getRepository(Ad::class)->count([]);

        $data = [
            'total' => $adsCount,
            'rows' => $this->adsEntityArrayToArray($ads)
        ];

        return $this->json($data);
    }

    /**
     * @param Ad[] $ads
     * @return array
     */
    private function adsEntityArrayToArray(array $ads): array
    {
        $array = [];

        foreach ($ads as $ad) {
            $array[] = [
                'datetime' => $ad->getDatetime()->format('Y-m-d H:i:s'),
                'reporter' => $ad->getReporter(),
                'text' => $ad->getText()
            ];
        }

        return $array;
    }
}
