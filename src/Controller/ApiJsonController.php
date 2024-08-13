<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
//use App\Repository\TownRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\GeoApiService;
use App\Service\MeteoCptApiService;

class ApiJsonController extends AbstractController
{

    #[Route('/get-town-infos-from-apis', name: 'get_towns_infos_apis', methods: ['POST'])]
    public function getTownsInfosFromApis(
        //TownRepository $townRepository,
        Request $request,
        GeoApiService $geoApiService,
        MeteoCptApiService $meteoCptApiService
    ) {
        $population = 0;
        $altitude = 0;
        $data = json_decode($request->getContent(), true);

        $townCode = $data['townCode'];
        $result = $geoApiService->callGeoApi($townCode);
        $totalData = json_decode($result, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $population = $totalData['population'];
        }

        $resultMeteoCpt = $meteoCptApiService->callEphemerideMeteoCptApi($townCode);
        $dataMeteoCpt = json_decode($resultMeteoCpt, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $altitude = $dataMeteoCpt['city']['altitude'];
        }

        //$toReturn = new \stdClass();
        //$toReturn->population = $population;
        $toReturn = [
            'population' => $population,
            'altitude' => $altitude
        ];
        return new JsonResponse($toReturn); 
        // améliorer : renvoyer un objet typé
    }

    #[Route('/get-town-forecast-from-apis', name: 'get_towns_forecast_apis', methods: ['POST'])]
    public function getTownsForecastFromApis(
        //TownRepository $townRepository,
        Request $request,
        MeteoCptApiService $meteoCptApiService
    ) {
        $requestData = json_decode($request->getContent(), true);
        $townCode = $requestData['townCode'];
        $result = $meteoCptApiService->callForecastMeteoCptApi($townCode);
        $data = json_decode($result, true);
        return new JsonResponse($data); 
    }
}