<?php

namespace App\Dto\ApiFranceTravail;

class ContactDto {

    private $nom;
    private $coordonnees1;
    private $urlPostulation;

    public function __construct() {}

    public function hydrate(array $data) {
        $this->nom = isset($data['nom']) ? $data['nom'] : '';
        $this->coordonnees1 = isset($data['coordonnees1']) ? $data['coordonnees1'] : '';
        $this->urlPostulation = isset($data['urlPostulation']) ? $data['urlPostulation'] : '';
    }

    public function serialize() {
        return [
            'nom' => $this->nom,
            'coordonnees1' => $this->coordonnees1,
            'urlPostulation' => $this->urlPostulation
        ];
    }
}

/*

"contact": {
        "nom": "RANDSTAD ind.tert.transp. infor. - M. Valentin SIMON",
        "coordonnees1": "https://www.randstad.fr/offre/001-TKV-0000857_02C/A?utm_source=pole-emploi&utm_medium=jobboard&utm_campaign=offres",
        "urlPostulation": "https://www.randstad.fr/offre/001-TKV-0000857_02C/A?utm_source=pole-emploi&utm_medium=jobboard&utm_campaign=offres"
    },

    */