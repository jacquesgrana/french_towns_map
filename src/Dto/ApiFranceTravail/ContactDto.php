<?php

namespace App\Dto\ApiFranceTravail;

class ContactDto {

    private string $nom;
    private string $coordonnees1;
    private string $coordonnees2;
    private string $coordonnees3;
    private string $telephone;
    private string $courriel;
    private string $commentaire;
    private string $urlRecruteur; 
    private string $urlPostulation;

    public function __construct() {
        $this->nom = '';
        $this->coordonnees1 = '';
        $this->coordonnees2 = '';
        $this->coordonnees3 = '';
        $this->telephone = '';
        $this->courriel = '';
        $this->commentaire = '';
        $this->urlRecruteur = '';
        $this->urlPostulation = '';
    }

    public function hydrate(array $data) {
        $this->nom = isset($data['nom']) ? $data['nom'] : '';
        $this->coordonnees1 = isset($data['coordonnees1']) ? $data['coordonnees1'] : '';
        $this->coordonnees2 = isset($data['coordonnees2']) ? $data['coordonnees2'] : '';
        $this->coordonnees3 = isset($data['coordonnees3']) ? $data['coordonnees3'] : '';
        $this->telephone = isset($data['telephone']) ? $data['telephone'] : '';
        $this->courriel = isset($data['courriel']) ? $data['courriel'] : '';
        $this->commentaire = isset($data['commentaire']) ? $data['commentaire'] : '';
        $this->urlRecruteur = isset($data['urlRecruteur']) ? $data['urlRecruteur'] : '';
        $this->urlPostulation = isset($data['urlPostulation']) ? $data['urlPostulation'] : '';
    }

    public function serialize() {
        return [
            'nom' => $this->nom,
            'coordonnees1' => $this->coordonnees1,
            'coordonnees2' => $this->coordonnees2,
            'coordonnees3' => $this->coordonnees3,
            'telephone' => $this->telephone,
            'courriel' => $this->courriel,
            'commentaire' => $this->commentaire,
            'urlRecruteur' => $this->urlRecruteur,
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