<?php

namespace App\Controller;

use App\Dto\ApiFranceTravail\EmploymentOfferDto;
use App\Dto\ApiFranceTravail\FiltreDto;
use App\Service\FranceTravailApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;


class EmploymentJsonController extends AbstractController {

    #[Route('/get-employment-by-town-from-api', name: 'get_employment_by_town', methods: ['POST'])]
    public function getEmploymentByTownFromApi(
        Request $request,
        FranceTravailApiService $franceTravailApiService
        ): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);
        $townCode = $data['townCode'];
        $result = $franceTravailApiService->getOffersByTownFromApi($townCode);
        $newOffers = [];
        $newFilters = [];

        if(isset($result['resultats'])) {
            $offers = $result['resultats'];
            foreach ($offers as $offer) {
                $newOffer = new EmploymentOfferDto();
                $newOffer->hydrate($offer);
                $newOffers[] = $newOffer->serialize();
            }
        }
        
        if(isset($result['filtresPossibles'])) {
            $filters = $result['filtresPossibles'];
            foreach ($filters as $filter) {
                $newFilter = new FiltreDto();
                $newFilter->hydrate($filter);
                $newFilters[] = $newFilter->serialize();
            }
        }
        return new JsonResponse(['offers' => $newOffers, 'filters' => $newFilters ], 200);
    }
}