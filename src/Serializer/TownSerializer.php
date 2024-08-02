<?php

namespace App\Serializer;

use App\Entity\Town;

class TownSerializer
{

    public function __construct()
    {
    }

    public function serializeFavoriteTowns(array $towns): array
    {
        $favoriteTownsArray = array_map(function($town) {
            return [
                'id' => $town->getId(),
                'townName' => $town->getTownName(),
                'townZipCode' => $town->getTownZipCode(),
                'townDptName' => $town->getDepartement()->getDepName(),
                'townRegName' => $town->getDepartement()->getRegion()->getRegName()
            ];
        }, $towns);
        return $favoriteTownsArray;
    }

    public function serializeTown(Town $town): array   
    {
        $toReturn  = [
            'id' => $town->getId(),
            'townCode' => $town->getTownCode(),
            'townZipCode' => $town->getTownZipCode(),
            'townName' => $town->getTownName(),
            'depName' => $town->getDepartement()->getDepName(),
            'regName' => $town->getDepartement()->getRegion()->getRegName(),
            'latitude' => $town->getPositionGps()->getLatitude(),
            'longitude' => $town->getPositionGps()->getLongitude()
        ];
        return $toReturn;
    }
}

/*
            t.id, 
            t.townCode, 
            t.townZipCode, 
            t.townName, 
            d.depName, 
            r.regName, 
            p.latitude, 
            p.longitude 
*/