<?php

namespace App\Dto\ApiFranceTravail;

class PartenaireDto
{
    private string $nom;
    private string $url;
    private string $logo;

    public function __construct()
    {
        $this->nom = '';
        $this->url = '';
        $this->logo = '';
    }

    public function hydrate(array $data)
    {
        $this->nom = isset($data['nom']) ? $data['nom'] : '';
        $this->url = isset($data['url']) ? $data['url'] : '';
        $this->logo = isset($data['logo']) ? $data['logo'] : '';
    }

    public function serialize()
    {
        return [
            'nom' => $this->nom,
            'url' => $this->url,
            'logo' => $this->logo
        ];
    }
}