<?php

namespace App\Dto\ApiFranceTravail;

class SalaireDto
{
    private string $libelle;

    public function __construct() {}

    public function hydrate(array $data) {
        $this->libelle = isset($data['libelle']) ? $data['libelle'] : '';
    }

    public function serialize() {
        return [
            'libelle' => $this->libelle
        ];
    }
}