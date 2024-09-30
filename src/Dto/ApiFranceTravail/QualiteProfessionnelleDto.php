<?php

namespace App\Dto\ApiFranceTravail;

class QualiteProfessionnelleDto
{
    private string $libelle;
    private string $description;

    public function __construct()
    {
        $this->libelle = '';
        $this->description = '';
    }

    public function hydrate(array $data)
    {
        $this->libelle = isset($data['libelle']) ? $data['libelle'] : '';
        $this->description = isset($data['description']) ? $data['description'] : '';
    }

    public function serialize()
    {
        return [
            'libelle' => $this->libelle,
            'description' => $this->description
        ];
    }
}