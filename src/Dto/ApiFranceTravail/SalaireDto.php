<?php

namespace App\Dto\ApiFranceTravail;

class SalaireDto
{
    private string $libelle;
    private string $commentaire;
    private string $complement1;
    private string $complement2;

    public function __construct() {
        $this->libelle = '';
        $this->commentaire = '';
        $this->complement1 = '';
        $this->complement2 = '';
    }

    public function hydrate(array $data) {
        $this->libelle = isset($data['libelle']) ? $data['libelle'] : '';
        $this->commentaire = isset($data['commentaire']) ? $data['commentaire'] : '';
        $this->complement1 = isset($data['complement1']) ? $data['complement1'] : '';
        $this->complement2 = isset($data['complement2']) ? $data['complement2'] : '';
    }

    public function serialize() {
        return [
            'libelle' => $this->libelle,
            'commentaire' => $this->commentaire,
            'complement1' => $this->complement1,
            'complement2' => $this->complement2
        ];
    }
}