<?php

namespace App\Controller;

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
        return new JsonResponse($result);
    }
}