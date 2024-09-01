<?php

namespace App\Dto\ApiFranceTravail;

class FormationDto
{

    private $codeFormation;
    private $domaineLibelle;
    private $niveauLibelle;
    private $exigence;
    public function __construct()
    {
    }

    public function hydrate(array $data)
    {
        $this->codeFormation = isset($data['codeFormation']) ? $data['codeFormation'] : '';
        $this->domaineLibelle = isset($data['domaineLibelle']) ? $data['domaineLibelle'] : '';
        $this->niveauLibelle = isset($data['niveauLibelle']) ? $data['niveauLibelle'] : '';
        $this->exigence = isset($data['exigence']) ? $data['exigence'] : '';
    }

    public function serialize()
    {
        return [
            'codeFormation' => $this->codeFormation,
            'domaineLibelle' => $this->domaineLibelle,
            'niveauLibelle' => $this->niveauLibelle,
            'exigence' => $this->exigence
        ];
    }
}



/*

{
            "codeFormation": "41062",
            "domaineLibelle": "banque",
            "niveauLibelle": "Bac ou équivalent",
            "exigence": "S"
        }

        */