<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Service\DataCultureGouvApiService;
use App\Dto\ApiDataCultureGouv\MuseumDto;

class CultureJsonController extends AbstractController
{

    #[Route('/get-museums-by-town-from-api', name: 'get_museums_by_town_from_api', methods: ['GET'])]
    public function getMuseumsByTownFromApi(
        Request $request,
        DataCultureGouvApiService $dataCultureGouvApiService
        )
        
    {
        $data = json_decode($request->getContent(), true);
        $townCode = $data['townCode'];
        $limit = $data['limit'];
        $offset = $data['offset'];
        
        $result = $dataCultureGouvApiService->getMuseumsByTown($townCode, $limit, $offset);
        // serializer $result avec les dto
        

        
        $totalCount = isset($result['total_count']) ? $result['total_count'] : 0;
        
        //$results = $result['results'];
        $tabToReturn = [];
        if(isset($result['results']) && !empty($result['results'])) {
            foreach ($result['results'] as $museum) {
                $newMuseum = new MuseumDto();
                $newMuseum->hydrate($museum);
                $tabToReturn[] = $newMuseum->serialize();
            }
        }
        $response = [
            'total_count' => $totalCount,
            'results' => $tabToReturn
        ];

        return new JsonResponse($response, 200);
    }
}