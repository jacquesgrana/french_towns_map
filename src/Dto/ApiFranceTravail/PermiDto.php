<?php

namespace App\Dto\ApiFranceTravail;

class PermiDto {

    private string $libelle;
    private string $exigence;

    public function __construct()
    {
        $this->libelle = '';
        $this->exigence = '';
    }

    public function hydrate(array $data)
    {
        $this->libelle = isset($data['libelle']) ? $data['libelle'] : '';
        $this->exigence = isset($data['exigence']) ? $data['exigence'] : '';
    }

    public function serialize()
    {
        return [
            'libelle' => $this->libelle,
            'exigence' => $this->exigence
        ];
    }
}