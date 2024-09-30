<?php

namespace App\Dto\ApiFranceTravail;

class LieuTravailDto
{
    private string  $libelle;
    private float   $latitude;
    private float   $longitude;
    private string  $codePostal;
    private string  $commune;

    public function __construct() {
        $this->libelle = '';
        $this->latitude = 0.0;
        $this->longitude = 0.0;
        $this->codePostal = '';
        $this->commune = '';
    }

    public function hydrate(array $data) {
        $this->libelle = isset($data['libelle']) ? $data['libelle'] : '';
        $this->latitude = isset($date['latitude']) ? $data['latitude'] : 0.0;
        $this->longitude = isset($data['longitude']) ? $data['longitude'] : 0.0;
        $this->codePostal = isset($data['codePostal']) ? $data['codePostal'] : '';
        $this->commune = isset($data['commune']) ? $data['commune'] : '';
    }

    public function serialize() {
        return get_object_vars($this);
    }


    public function getLibelle(): string
    {
        return $this->libelle;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function getCodePostal(): string
    {
        return $this->codePostal;
    }

    public function getCommune(): string
    {
        return $this->commune;
    }

    // setters

    public function setLibelle(string $libelle): LieuTravailDto
    {
        $this->libelle = $libelle;
        return $this;
    }

    public function setLatitude(float $latitude): LieuTravailDto
    {
        $this->latitude = $latitude;
        return $this;
    }

    public function setLongitude(float $longitude): LieuTravailDto
    {
        $this->longitude = $longitude;
        return $this;
    }

    public function setCodePostal(string $codePostal): LieuTravailDto
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    public function setCommune(string $commune): LieuTravailDto
    {
        $this->commune = $commune;
        return $this;
    }
}

/*

"lieuTravail": {
                "libelle": "34 - Montpellier",
                "latitude": 43.610476,
                "longitude": 3.87048,
                "codePostal": "34000",
                "commune": "34172"
            },

*/