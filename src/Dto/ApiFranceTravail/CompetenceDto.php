<?php

namespace App\Dto\ApiFranceTravail;

class CompetenceDto {

    private string $code;
    private string $libelle;
    private string $exigence;

    public function __construct()
    {
        $this->code = '';
        $this->libelle = '';
        $this->exigence = '';
    }

    public function hydrate(array $data)
    {
        $this->code = isset($data['code']) ? $data['code'] : '';
        $this->libelle = isset($data['libelle']) ? $data['libelle'] : '';
        $this->exigence = isset($data['exigence']) ? $data['exigence'] : '';
    }

    public function serialize()
    {
        return [
            'code' => $this->code,
            'libelle' => $this->libelle,
            'exigence' => $this->exigence
        ];
    }
}