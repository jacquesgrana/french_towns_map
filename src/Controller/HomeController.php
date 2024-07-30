<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\TownRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(TownRepository $townRepository): Response
    {
        //$townsGps = $townRepository->findByBoundingBox(43.520769, 3.752716, 43.700769, 4.000716);

        //$townsName = $townRepository->findByName('Montp');

        return $this->render('home/index.html.twig', 
        [
            //'townsGps' => $townsGps,
            //'townsName' => $townsName
        ]);
    }

    #[Route('/', name: 'app_home_redirect')]
    public function redirectToHome(): Response
    {
        return $this->redirectToRoute('app_home');
    }

    /*
    #[Route('/get-towns-by-bounds', name: 'get_towns_by_bounds', methods: ['POST'])]
    public function getTownsByBounds(
        TownRepository $townRepository,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $sw_lat = $data['sw_lat'];
        $sw_lng = $data['sw_lng'];
        $ne_lat = $data['ne_lat'];
        $ne_lng = $data['ne_lng'];

        return new JsonResponse($townRepository->findByBoundingBox($sw_lat, $sw_lng, $ne_lat, $ne_lng));
    }


    #[Route('/get-towns-by-name', name: 'get_towns_by_name', methods: ['POST'])]
    public function getTownsByName(
        TownRepository $townRepository,
        Request $request
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $searchRequest = $data['searchRequest'];

        return new JsonResponse($townRepository->findByName($searchRequest));
    }
    */
}
