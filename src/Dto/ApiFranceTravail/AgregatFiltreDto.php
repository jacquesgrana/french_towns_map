<?php

namespace App\Dto\ApiFranceTravail;

class AgregatFiltreDto
{

    public string $valeurPossible;
    public int $nbResultats;


    public function __construct()
    {
        $this->valeurPossible = '';
        $this->nbResultats = -1;
    }

    public function hydrate(array $data)
    {
        $this->valeurPossible = isset($data['valeurPossible']) ? $data['valeurPossible'] : '';
        $this->nbResultats = isset($data['nbResultats']) ? $data['nbResultats'] : -1;
    }

    public function serialize(): array
    {
        return [
            'valeurPossible' => $this->valeurPossible,
            'nbResultats' => $this->nbResultats
        ];
    }

}

/*

{
                "valeurPossible": "CCE",
                "nbResultats": 18
            },

*/