<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
//use App\Repository\TownRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\GeoApiService;

class ApiController extends AbstractController
{

    #[Route('/get-town-infos-from-apis', name: 'get_towns_infos_apis', methods: ['POST'])]
    public function getTownsByBounds(
        //TownRepository $townRepository,
        Request $request,
        GeoApiService $geoApiService
    ) {
        $data = json_decode($request->getContent(), true);

        $townCode = $data['townCode'];
        $result = $geoApiService->callGeoApi($townCode);

        $totalData = json_decode($result, true);
        $population = 0;
        // Vérifier si le décodage a réussi
        if (json_last_error() === JSON_ERROR_NONE) {
            // Extraire la population
            $population = $totalData['population'];
        }
        //$population = $result->population;
        $toReturn = new \stdClass();
        $toReturn->population = $population;
        return new JsonResponse($toReturn); 
        // améliorer : renvoyer un objet
    }

}