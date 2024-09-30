<?php

namespace App\Dto\ApiFranceTravail;

class FormationDto
{

    private $codeFormation;
    private $domaineLibelle;
    private $niveauLibelle;
    private $commentaire;
    private $exigence;
    public function __construct()
    {
        $this->codeFormation = '';
        $this->domaineLibelle = '';
        $this->niveauLibelle = '';
        $this->commentaire = '';
        $this->exigence = '';
    }

    public function hydrate(array $data)
    {
        $this->codeFormation = isset($data['codeFormation']) ? $data['codeFormation'] : '';
        $this->domaineLibelle = isset($data['domaineLibelle']) ? $data['domaineLibelle'] : '';
        $this->niveauLibelle = isset($data['niveauLibelle']) ? $data['niveauLibelle'] : '';
        $this->commentaire = isset($data['commentaire']) ? $data['commentaire'] : '';
        $this->exigence = isset($data['exigence']) ? $data['exigence'] : '';
    }

    public function serialize()
    {
        return [
            'codeFormation' => $this->codeFormation,
            'domaineLibelle' => $this->domaineLibelle,
            'niveauLibelle' => $this->niveauLibelle,
            'commentaire' => $this->commentaire,
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