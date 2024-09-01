<?php

namespace App\Dto\ApiFranceTravail;

use App\Dto\ApiFranceTravail\AgregatFiltreDto;

class FiltreDto
{
    private string $filtre;
    private array $agregation;

    public function __construct() {
    }

    public function hydrate(array $data) {
        $this->filtre = isset($data['filtre']) ? $data['filtre'] : '';
        $this->agregation = [];
        if(isset($data['agregation'])) {
            foreach ($data['agregation'] as $agregation) {
                $newAgregat = new AgregatFiltreDto();
                $newAgregat->hydrate($agregation);
                $this->agregation[] = $newAgregat->serialize();
            }
        }
    }

    public function serialize() {
        return [
            'filtre' => $this->filtre,
            'agregation' => $this->agregation
        ];
    }
}




/*
[
    {
        "filtre": "typeContrat",
        "agregation": [
            {
                "valeurPossible": "CCE",
                "nbResultats": 18
            },
            {
                "valeurPossible": "CDD",
                "nbResultats": 745
            },
            {
                "valeurPossible": "CDI",
                "nbResultats": 3180
            },
            {
                "valeurPossible": "DDI",
                "nbResultats": 1
            },
            {
                "valeurPossible": "DIN",
                "nbResultats": 5
            },
            {
                "valeurPossible": "FRA",
                "nbResultats": 23
            },
            {
                "valeurPossible": "LIB",
                "nbResultats": 102
            },
            {
                "valeurPossible": "MIS",
                "nbResultats": 781
            },
            {
                "valeurPossible": "SAI",
                "nbResultats": 14
            },
            {
                "valeurPossible": "TTI",
                "nbResultats": 4
            }
        ]
    },
    {
        "filtre": "experience",
        "agregation": [
            {
                "valeurPossible": "0",
                "nbResultats": 422
            },
            {
                "valeurPossible": "4",
                "nbResultats": 1775
            },
            {
                "valeurPossible": "1",
                "nbResultats": 225
            },
            {
                "valeurPossible": "2",
                "nbResultats": 2090
            },
            {
                "valeurPossible": "3",
                "nbResultats": 362
            }
        ]
    },
    {
        "filtre": "qualification",
        "agregation": [
            {
                "valeurPossible": "0",
                "nbResultats": 1860
            },
            {
                "valeurPossible": "9",
                "nbResultats": 291
            },
            {
                "valeurPossible": "X",
                "nbResultats": 2723
            }
        ]
    },
    {
        "filtre": "natureContrat",
        "agregation": [
            {
                "valeurPossible": "CC",
                "nbResultats": 1
            },
            {
                "valeurPossible": "CI",
                "nbResultats": 1
            },
            {
                "valeurPossible": "CU",
                "nbResultats": 10
            },
            {
                "valeurPossible": "E1",
                "nbResultats": 4556
            },
            {
                "valeurPossible": "E2",
                "nbResultats": 128
            },
            {
                "valeurPossible": "FA",
                "nbResultats": 1
            },
            {
                "valeurPossible": "FS",
                "nbResultats": 29
            },
            {
                "valeurPossible": "I1",
                "nbResultats": 5
            },
            {
                "valeurPossible": "NS",
                "nbResultats": 143
            }
        ]
    }
]
*/