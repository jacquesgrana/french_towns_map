<?php

/**
 * Classe d'utilisation de l'API FranceTravail.fr
 * utilise : https://entreprise.francetravail.fr et https://api.francetravail.io
 * gestion du token de connexion de l'api et de sa validité avec la bdd pour que les instances du serveur utilisent le même token
 */

namespace App\Service;

use App\Repository\AccessTokenApiFTRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\AccessTokenApiFT;

class FranceTravailApiService
{
    private string $clientId;
    private string $clientSecret;
    private string $apiConnectUrl;
    private string $apiRequestUrl;
    private ?string $accessToken = null;
    private ?int $tokenValidUntilTS = null; // à enlever qd l'entité sera créée et ok

    public function __construct(
        private EntityManagerInterface $em, 
        private AccessTokenApiFTRepository $accessTokenApiFTRepository
        )
    {
        $this->clientId = $_ENV['API_FT_CLIENT_ID'];
        $this->clientSecret = $_ENV['API_FT_CLIENT_SECRET'];
        $this->apiConnectUrl = "https://entreprise.francetravail.fr/connexion/oauth2/access_token?realm=partenaire";
        $this->apiRequestUrl = "https://api.francetravail.io/partenaire/offresdemploi/v2/offres/search";
    }

    private function connectToApiAndSetToken(): void
    {
        $url = $this->apiConnectUrl;

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, "grant_type=client_credentials&client_id=" . $this->clientId . "&client_secret=" . $this->clientSecret . "&scope=api_offresdemploiv2 o2dsoffre");
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));

        $result = curl_exec($curl);

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            throw new \Exception("Erreur cURL lors de la connexion à l'API : " . $error_msg);
        }

        curl_close($curl);

        $data = json_decode($result, true);
        if (!isset($data['access_token'])) {
            throw new \Exception("Impossible de récupérer le token d'accès : " . $result);
        }

        $this->accessToken = $data['access_token'];
        $this->tokenValidUntilTS = time() + ($data['expires_in'] - 10);

        // sauvegarder le token dans la bdd avec le ts d'expiration
        $accessToken = new AccessTokenApiFT();
        $accessToken->setToken($this->accessToken);
        $accessToken->setValidUntilTS($this->tokenValidUntilTS);
        $this->em->persist($accessToken);
        $this->em->flush();
    }

    public function getAccessToken(): string
    {
        $accessToken = $this->accessTokenApiFTRepository->findLastValidTokenAndCleanTable();
        if ($accessToken !== null) {
            $this->accessToken = $accessToken->getToken();
            $this->tokenValidUntilTS = $accessToken->getValidUntilTS();
            //return $this->accessToken;
        }
        else {
            $this->connectToApiAndSetToken();
            //return $this->accessToken;
        }
        return $this->accessToken;
        /*
        if ($this->accessToken === null || $this->tokenValidUntilTS === null || time() > $this->tokenValidUntilTS) {
            $this->connectToApiAndSetToken();
        }
        return $this->accessToken;
        */
    }

    public function getOffersByTownFromApi(string $townCode): array
    {
        $url = $this->apiRequestUrl . '?commune=' . $townCode; // . '&range=0-149'
        //dd($url);
        // TODO : faire méthode
        
        /*
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->getAccessToken(), 'Content-Type: application/json'));
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            throw new \Exception("Erreur cURL lors de la connexion à l'API : " . $error_msg);
        }
        curl_close($curl);
        $data = json_decode($result, true);
        return $data;
        */
        return $this->callUrlByCurl($url);
    }

    public function getOffersByTownForDatatable($townCode, $start, $end) {
        $url = $this->apiRequestUrl . '?commune=' . $townCode . '&range=' . $start . '-' . $end;

        // TODO : faire méthode
        /*
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->getAccessToken(), 'Content-Type: application/json'));
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            curl_close($curl);
            throw new \Exception("Erreur cURL lors de la connexion à l'API : " . $error_msg);
        }
        curl_close($curl);
        $data = json_decode($result, true);

        return $data;
        */
        return $this->callUrlByCurl($url);
    }

    public function callUrlByCurl($url) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->getAccessToken(), 'Content-Type: application/json'));
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
