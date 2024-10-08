<?php

namespace App\Service;

class DataEduGouvService
{
    private string $dataEduGouvUrlStart;
    private string $dataEduGouvUrlEnd;

    public function __construct()
    {
        $this->dataEduGouvUrlStart = "https://data.education.gouv.fr/api/explore/v2.1/catalog/datasets/fr-en-annuaire-education/records?where=code_commune%3D";
        $this->dataEduGouvUrlEnd = "&limit=100&offset=50";

        ///api/explore/v2.1/catalog/datasets/fr-en-annuaire-education/records?where=code_commune%3D77288&order_by=nom_etablissement&limit=20
    }

    public function getSchoolsByCodeCommune(string $code_commune, int $limit = 100, int $offset = 0, string $orderBy = 'nom_etablissement', string $orderType = 'ASC', array $filtersTab = [])
    {
        //$filtersTab = ['lycee_agricole'];
        $filters = '';
        if(count($filtersTab) > 0) {
            $filters = '%20AND%20(';
            foreach ($filtersTab as $filter) {
                $filters .= $filter . '%3D1%20OR%20';
            }
            $filters = substr($filters, 0, -8);
            $filters .= ')';
        }
        //dd($filters);
        //$filters = '%20AND%20(segpa%3D1%20OR%20lycee_agricole%3D1)';
        $url = $this->dataEduGouvUrlStart . $code_commune . $filters . '&limit=' . $limit . '&offset=' . $offset . '&order_by=' . $orderBy . '%20' . $orderType;
        //dd($url);
        $curl = curl_init();

        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // EXECUTE:
        $result = curl_exec($curl);

        // Check for errors
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
        }

        curl_close($curl);

        if (isset($error_msg)) {
            // Handle cURL error
            return ['error' => $error_msg];
        }
        return $result;
        
    }
}

/*
https://data.education.gouv.fr/api/explore/v2.1/catalog/datasets/fr-en-annuaire-education/records?where=code_commune%3D77288&limit=20
*/