<?php

namespace App\Controller;

use App\Service\DataEduGouvService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Dto\SchoolDto;

class SchoolJsonController extends AbstractController {

    #[Route('/get-schools-by-town-from-api', name: 'get_schools_by_town', methods: ['POST'])]
    public function getSchoolsByTownFromApi(
        Request $request,
        DataEduGouvService $dataEduGouvService
        ): JsonResponse 
    {
        $data = json_decode($request->getContent(), true);
        $townCode = $data['townCode'];
        $limit = $data['limit'];
        $offset = $data['offset'];
        $order_by = $data['order_by'];
        $order_by_type = $data['order_by_type'];
        $filters = $data['filters'];
        //$order_by_type = 'ASC';
        //$filtersString = 'lycee_agricole:restauration';
        $filtersTab = $filters !== '' ? explode(':', $filters) : [];
        //$filtersTab = ['lycee_agricole'];
        $result = $dataEduGouvService->getSchoolsByCodeCommune($townCode, $limit, $offset, $order_by, $order_by_type, $filtersTab);
        $data = json_decode($result, true);
        $totalCount = $data['total_count'];
        $resultArray = $data['results'];
        //dd($resultArray);
        $toReturn = [];
        foreach ($resultArray as $school) {
            $schoolDto = new SchoolDto();
            $schoolDto->hydrate($school);
            $toReturn[] = $schoolDto->serialize();
        }
        return new JsonResponse(['schools' => $toReturn, 'totalCount' => $totalCount], 200);
    }
}