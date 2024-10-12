<?php

namespace App\Service;

use App\Repository\TownRepository;

class DataCultureGouvApiService
{
    private string $dataCultureGouvUrl;
    

    public function __construct(private TownRepository $townRepository)
    {
        $this->dataCultureGouvUrl = "https://data.culture.gouv.fr/api/explore/v2.1/catalog/datasets/musees-de-france-base-museofile/records?where=code_postal%3D";
        //$this->townRepository = new TownRepository();
    }

    public function getMuseumsByTown(string $townCode, int $limit = 10, int $offset = 0): array {
        $town = $this->townRepository->findOneBy(['townCode' => $townCode]);
        $townZipCode = $town->getTownZipCode();
        $url = $this->dataCultureGouvUrl . $townZipCode . '&limit=' . $limit . '&offset=' . $offset;
        return $this->callUrlByCurl($url);
    }

    public function callUrlByCurl($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl); 
            curl_close($curl);
            throw new \Exception("Erreur cURL lors de la connexion à l'API : " . $error_msg);
        }
        curl_close($curl);
        $data = json_decode($result, true);
        return $data;
    }
}