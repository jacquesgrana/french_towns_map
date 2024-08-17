<?php

namespace App\Dto;

class ForecastDto
{
    private int $day;
    private string $datetime;
    private float $wind10m;
    private float $gust10m;
    private int $dirwind10m;
    private float $rr10;
    private float $rr1;
    private int $probarain;
    private int $weather;
    private int $tmin;
    private int $tmax;
    private int $sun_hours;
    private float $etp;
    private int $probafrost;
    private int $probafog;
    private int $probawind70;
    private int $probawind100;
    private int $gustx;

    public function __construct() {
    }

    public function hydrate(array $data) {
        $this->day = $data['day'];
        $this->datetime = $data['datetime'];
        $this->wind10m = $data['wind10m'];
        $this->gust10m = $data['gust10m'];
        $this->dirwind10m = $data['dirwind10m'];
        $this->rr10 = $data['rr10'];
        $this->rr1 = $data['rr1'];
        $this->probarain = $data['probarain'];
        $this->weather = $data['weather'];
        $this->tmin = $data['tmin'];
        $this->tmax = $data['tmax'];
        $this->sun_hours = $data['sun_hours'];
        $this->etp = $data['etp'];
        $this->probafrost = $data['probafrost'];
        $this->probafog = $data['probafog'];
        $this->probawind70 = $data['probawind70'];
        $this->probawind100 = $data['probawind100'];
        $this->gustx = $data['gustx'];
    }

    public function serialize() {
        return get_object_vars($this);
    }

    // getters : enlever ?
/*
    public function getDay(): int
    {
        return $this->day;
    }

    public function getDatetime(): string
    {
        return $this->datetime;
    }

    public function getWind10m(): float
    {
        return $this->wind10m;
    }

    public function getGust10m(): float
    {
        return $this->gust10m;
    }

    public function getDirwind10m(): int
    {
        return $this->dirwind10m;
    }

    public function getRr10(): float
    {
        return $this->rr10;
    }

    public function getRr1(): float
    {
        return $this->rr1;
    }

    public function getProbarain(): int
    {
        return $this->probarain;
    }

    public function getWeather(): int
    {
        return $this->weather;
    }

    public function getTmin(): int
    {
        return $this->tmin;
    }

    public function getTmax(): int
    {
        return $this->tmax;
    }

    public function getSunHours(): int
    {
        return $this->sun_hours;
    }

    public function getEtp(): float
    {
        return $this->etp;
    }

    public function getProbafrost(): int
    {
        return $this->probafrost;
    }

    public function getProbafog(): int
    {
        return $this->probafog;
    }

    public function getProbawind70(): int
    {
        return $this->probawind70;
    }

    public function getProbawind100(): int
    {
        return $this->probawind100;
    }

    public function getGustx(): int
    {
        return $this->gustx;
    }
*/


    // setters : enlever ?
/*
    public function setDay(int $day): ForecastDto
    {
        $this->day = $day;
        return $this;
    }

    public function setDatetime(string $datetime): ForecastDto
    {
        $this->datetime = $datetime;
        return $this;
    }

    public function setWind10m(float $wind10m): ForecastDto
    {
        $this->wind10m = $wind10m;
        return $this;
    }

    public function setGust10m(float $gust10m): ForecastDto
    {
        $this->gust10m = $gust10m;
        return $this;
    }

    public function setDirwind10m(int $dirwind10m): ForecastDto
    {
        $this->dirwind10m = $dirwind10m;
        return $this;
    }

    public function setRr10(float $rr10): ForecastDto
    {
        $this->rr10 = $rr10;
        return $this;
    }

    public function setRr1(float $rr1): ForecastDto
    {
        $this->rr1 = $rr1;
        return $this;
    }

    public function setProbarain(int $probarain): ForecastDto
    {
        $this->probarain = $probarain;
        return $this;
    }

    public function setWeather(int $weather): ForecastDto
    {
        $this->weather = $weather;
        return $this;
    }

    public function setTmin(int $tmin): ForecastDto
    {
        $this->tmin = $tmin;
        return $this;
    }  

    public function setTmax(int $tmax): ForecastDto
    {
        $this->tmax = $tmax;
        return $this;
    }

    public function setSunHours(int $sun_hours): ForecastDto
    {
        $this->sun_hours = $sun_hours;
        return $this;
    }

    public function setEtp(float $etp): ForecastDto
    {
        $this->etp = $etp;
        return $this;
    }

    public function setProbafrost(int $probafrost): ForecastDto
    {
        $this->probafrost = $probafrost;
        return $this;
    }

    public function setProbafog(int $probafog): ForecastDto
    {
        $this->probafog = $probafog;
        return $this;
    }

    public function setProbawind70(int $probawind70): ForecastDto
    {
        $this->probawind70 = $probawind70;
        return $this;
    }

    public function setProbawind100(int $probawind100): ForecastDto
    {
        $this->probawind100 = $probawind100;
        return $this;
    }

    public function setGustx(int $gustx): ForecastDto
    {
        $this->gustx = $gustx;
        return $this;
    }
*/


}

/*
{
    "city": {
        "insee": "35238",
        "cp": 35000,
        "name": "Rennes",
        "latitude": 48.112,
        "longitude": -1.6819,
        "altitude": 38
    },
    "update": "2020-10-29T12:40:08+0100",
    "forecast": {
        "insee": "35238",
        "cp": 35000,
        "latitude": 48.112,
        "longitude": -1.6819,
        "day": 0, //Jour entre 0 et 13 (Pour le jour même : 0, pour le lendemain : 1, etc.)
        "datetime": "2020-10-29T01:00:00+0100",
        "wind10m": 15, //Vent moyen à 10 mètres en km/h
        "gust10m": 49, //Rafales de vent à 10 mètres en km/h
        "dirwind10m": 230, //Direction du vent en degrés (0 à 360°)
        "rr10": 0.2, //Cumul de pluie sur la journée en mm
        "rr1": 0.3, //Cumul de pluie maximal sur la journée en mm
        "probarain": 40,
        "weather": 4,
        "tmin": 11,
        "tmax": 17,
        "sun_hours": 1, //Ensoleillement en heures
        "etp": 1, //Cumul d'évapotranspiration en mm
        "probafrost": 0,
        "probafog": 0,
        "probawind70": 0,
        "probawind100": 0,
        "gustx": 49 //Rafale de vent potentielle sous orage ou grain en km/h
    }
}
*/