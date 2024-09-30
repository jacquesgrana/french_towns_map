<?php

namespace App\Dto\ApiFranceTravail;

class AgenceDto
{
    private string $telephone;
    private string $courriel;

    public function __construct() {
        $this->telephone = '';
        $this->courriel = '';
    }

    public function hydrate(array $data) {
        $this->telephone = isset($data['telephone']) ? $data['telephone'] : '';
        $this->courriel = isset($data['courriel']) ? $data['courriel'] : '';
    }

    public function serialize() {
        return [
            'telephone' => $this->telephone,
            'courriel' => $this->courriel
        ];
    }
}