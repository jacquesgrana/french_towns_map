<?php

namespace App\Dto\ApiFranceTravail;

class OrigineOffreDto
{
    private string $origine;
    private string $urlOrigine;

    public function __construct() {
        $this->origine = '';
        $this->urlOrigine = '';
    }

    public function hydrate(array $data) {
        $this->origine = isset($data['origine']) ? $data['origine'] : '';
        $this->urlOrigine = isset($data['urlOrigine']) ? $data['urlOrigine'] : '';
    }

    public function serialize() {
        return get_object_vars($this);
    }
}


/*

 "origineOffre": {
        "origine": "1",
        "urlOrigine": "https://candidat.francetravail.fr/offres/recherche/detail/180HSFM"
    },

    */