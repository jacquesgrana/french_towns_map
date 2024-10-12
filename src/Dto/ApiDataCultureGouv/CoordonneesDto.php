<?php

namespace App\Dto\ApiDataCultureGouv;

class CoordonneesDto
{
    private float $lon;
    private float $lat;

    public function __construct() {
        $this->lon = 0.0;
        $this->lat = 0.0;
    }

    public function hydrate(array $data) {
        $this->lon = isset($data['lon']) ? $data['lon'] : 0.0;
        $this->lat = isset($data['lat']) ? $data['lat'] : 0.0;
    }

    public function serialize(): array {
        return [
            'lon' => $this->lon,
            'lat' => $this->lat
        ];
    }
}