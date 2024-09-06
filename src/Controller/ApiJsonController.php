<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;
//use App\Repository\TownRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\GeoApiService;
use App\Service\MeteoCptApiService;

use App\Dto\ForecastDto;

class ApiJsonController extends AbstractController
{

    #[Route('/get-town-infos-from-apis', name: 'get_towns_infos_apis', methods: ['POST'])]
    public function getTownsInfosFromApis(
        //TownRepository $townRepository,
        Request $request,
        GeoApiService $geoApiService,
        MeteoCptApiService $meteoCptApiService
    ): JsonResponse {
        $population = 0;
        $altitude = 0;
        $data = json_decode($request->getContent(), true);

        $townCode = $data['townCode'];
        $result = $geoApiService->callGeoApi($townCode); 

        if($result === null || is_array($result)) {
            $population = 0;
        }
        else {
            $totalData = json_decode($result, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $population = $totalData['population'] ?? 0;
            }
        }
            
            
        //dd($totalData);
        

        $resultMeteoCpt = $meteoCptApiService->callEphemerideMeteoCptApi($townCode);
        $dataMeteoCpt = json_decode($resultMeteoCpt, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            $altitude = $dataMeteoCpt['city']['altitude'] ?? 0;
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
    ): JsonResponse {
        $requestData = json_decode($request->getContent(), true);
        $townCode = $requestData['townCode'];
        $day = $requestData['day'];
        $result = $meteoCptApiService->callForecastMeteoCptApi($townCode, $day);
        $data = json_decode($result, true);
        $forecastDto = new ForecastDto();
        if(!isset($data['forecast'])) {
            return new JsonResponse(['error' => 'forecast is required'], 200);
        }
        else {
            $forecastDto->hydrate($data['forecast']);
            return new JsonResponse($forecastDto->serialize(), 200); 
        }
        
    }
}

/*
{
    "city": {
        "insee": "35238",
        "cp": 35000,
        "name": "Rennes",
        "latitude": 48.112,
        "longitude": -1.6819,
        "altitude": 38
    },
    "update": "2020-10-29T12:40:08+0100",
    "forecast": {
        "insee": "35238",
        "cp": 35000,
        "latitude": 48.112,
        "longitude": -1.6819,
        "day": 0, //Jour entre 0 et 13 (Pour le jour même : 0, pour le lendemain : 1, etc.)
        "datetime": "2020-10-29T01:00:00+0100",
        "wind10m": 15, //Vent moyen à 10 mètres en km/h
        "gust10m": 49, //Rafales de vent à 10 mètres en km/h
        "dirwind10m": 230, //Direction du vent en degrés (0 à 360°)
        "rr10": 0.2, //Cumul de pluie sur la journée en mm
        "rr1": 0.3, //Cumul de pluie maximal sur la journée en mm
        "probarain": 40,
        "weather": 4,
        "tmin": 11,
        "tmax": 17,
        "sun_hours": 1, //Ensoleillement en heures
        "etp": 1, //Cumul d'évapotranspiration en mm
        "probafrost": 0,
        "probafog": 0,
        "probawind70": 0,
        "probawind100": 0,
        "gustx": 49 //Rafale de vent potentielle sous orage ou grain en km/h
    }
}
*/