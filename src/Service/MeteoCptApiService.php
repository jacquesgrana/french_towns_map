<?php

namespace App\Service;

class MeteoCptApiService
{
    private string $meteoCptUrl;
    private string $meteoCptToken;
    public function __construct()
    {
        $this->meteoCptUrl = "https://api.meteo-concept.com";
        $this->meteoCptToken = "06a3e3f2f54d0caa80e3915bca02c559b6d425804cd52155164e57c6d48bd43e";
    }

    public function callMeteoCptApi($code)
    {
        $url = $this->meteoCptUrl . "/api/ephemeride/1?token=" . $this->meteoCptToken . "&insee=" . $code;
        $curl = curl_init();
        //${Config.METEO_CPT_URL}/api/ephemeride/1?token=${Config.METEO_CPT_TOKEN}&insee=${code_commune}

        /*
        // Si des données sont fournies, ajoutez-les à l'URL en tant que paramètres de requête
        if ($data) {
            $url = sprintf("%s?%s", $url, http_build_query($data));
        }
        */

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
const datas = await fetch(
      `${Config.GEO_API_URL}/communes/${code_commune}`,
      {
        method: "GET",
      }
    );

    const url = `${Config.METEO_CPT_URL}/api/ephemeride/1?token=${Config.METEO_CPT_TOKEN}&insee=${code_commune}`;

    return fetch(url, {
      //headers: headers,
      method: "GET",
    })

    public static GEO_API_URL: string = "https://geo.api.gouv.fr";
    public static METEO_CPT_URL: string = "https://api.meteo-concept.com";
    public static METEO_CPT_TOKEN: string = "06a3e3f2f54d0caa80e3915bca02c559b6d425804cd52155164e57c6d48bd43e";


    // Exemple d'utilisation
$url = 'https://api.example.com/users';
$params = ['param1' => 'value1', 'param2' => 'value2']; // Paramètres de requête

$response = callAPI($url, $params);
$data = json_decode($response, true);

if (isset($data['error'])) {
    echo 'Erreur cURL : ' . $data['error'];
} else {
    // Traitez les données de la réponse
    print_r($data);
}
 */
