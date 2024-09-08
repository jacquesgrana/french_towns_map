<?php

namespace App\Controller;

use App\Dto\ApiFranceTravail\EmploymentOfferDto;
use App\Dto\ApiFranceTravail\FiltreDto;
use App\Service\FranceTravailApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

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

    #[Route('/get-employment-by-town-for-datatable', name: 'get_employment_by_town_for_datatable', methods: ['POST'])]
    public function getEmploymentByTownForDatatable(
        Request $request, 
        FranceTravailApiService $franceTravailApiService
        ): JsonResponse
    {
        //$data = $request->request->all();
        
        $townCode = $request->request->get('townCode', $request->query->get('townCode', '34172'));
        $start = (int)$request->request->get('start', $request->query->get('start', 0));
        $length = (int)$request->request->get('length', $request->query->get('length', 10));
        $draw = (int)$request->request->get('draw', $request->query->get('draw', 1));
        $sort = (int)$request->request->get('sort', $request->query->get('sort', 2));
        $filters = $request->request->get('filters', $request->query->get('filters', ''));
        $end = $start + $length - 1;
        //$filters = '';
        //dd($townCode, $start, $end);
        $result = $franceTravailApiService->getOffersByTownForDatatable($townCode, $start, $end, $sort, $filters);

        if (!isset($result['resultats'])) {
            return new JsonResponse([], 203);
        }

        if(!isset($result['filtresPossibles'])) {
            return new JsonResponse(['error' => 'No filters found'], 500);
        }

        $filters = $result['filtresPossibles'];
        //$filterTypeContrat = [];
        foreach ($filters as $filter) {
            if($filter['filtre'] === 'typeContrat') {
                $filterTypeContrat = $filter;
            }
        }

        $totalFilteredOffers = 0;
        $filtersAgregation = $filterTypeContrat['agregation'];
        $totalOffers = 0;

        foreach ($filtersAgregation as $filter) {
            $totalOffers += $filter['nbResultats'];

            /*
            if($filter['valeurPossible'] === $codeTypeContrat) {
                $totalFilteredOffers = $filter['nbResultats'];
            }*/
        }
        $totalOffers = $totalOffers > 3000 ? 3000 : $totalOffers;
        $totalFilteredOffers = $totalFilteredOffers === 0 ? $totalOffers : $totalFilteredOffers;
        $totalFilteredOffers = $totalFilteredOffers > 3000 ? 3000 : $totalFilteredOffers;

        // récupérer les filtres possibles et appeler fonction qui calcule le nombre d'offres possibles et affecter "recordsTotal" et "recordsFiltered" dans $response

        $offers = $result['resultats'];
        $toReturn = array_map(function($offer) {
            $intitule = isset($offer['intitule']) ? $offer['intitule'] : '';
            $lieuTravailLibelle = isset($offer['lieuTravail']['libelle']) ? $offer['lieuTravail']['libelle'] : '';
            $romeLibelle = isset($offer['romeLibelle']) ? $offer['romeLibelle'] : '';
            $typeContratLibelle = isset($offer['typeContratLibelle']) ? $offer['typeContratLibelle'] : '';
            $secteurActiviteLibelle = isset($offer['secteurActiviteLibelle']) ? $offer['secteurActiviteLibelle'] : '';

            return [
                $intitule,
                $lieuTravailLibelle,
                $romeLibelle,
                $typeContratLibelle,
                $secteurActiviteLibelle
            ];
        }, $offers);

        $response = [
            "draw" => $draw,
            "recordsTotal" => $totalOffers,
            "recordsFiltered" => $totalFilteredOffers,
            "data" => $toReturn
        ];

        return new JsonResponse($response);
    }

    #[Route('/get-types-contrats-filters', name: 'get_type_contrat_filters', methods: ['GET'])]
    public function getTypeContratFilters(FranceTravailApiService $franceTravailApiService): JsonResponse
    {
        $result = $franceTravailApiService->getTypesContrats();
        return new JsonResponse($result, 200);
    }

    #[Route('/get-domaines-filters', name: 'get_domaines_filters', methods: ['GET'])]
    public function getDomaines(FranceTravailApiService $franceTravailApiService): JsonResponse
    {
        $result = $franceTravailApiService->getDomaines();
        return new JsonResponse($result, 200);
    }

}

/*
datatable : 

                row.push(offer.intitule);
                //row.push(offer.dateCreation);
                row.push(offer.lieuTravail.libelle);
                row.push(offer.romeLibelle);
                row.push(offer.typeContratLibelle);
                row.push(offer.secteurActiviteLibelle);

                columns: [
                    { title: 'Intitule' },
                    { title: 'Lieu' },
                    { title: 'Rome' },
                    { title: 'Type de contrat' },
                    { title: 'Secteur d\'activité' },
                ],
*/