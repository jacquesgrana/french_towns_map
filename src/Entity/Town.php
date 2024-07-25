<?php

namespace App\Entity;

use App\Repository\TownRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TownRepository::class)]
class Town
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 10)]
    private ?string $townCode = null;

    #[ORM\Column(length: 120)]
    private ?string $townName = null;

    #[ORM\Column(length: 20)]
    private ?string $townZipCode = null;

    #[ORM\ManyToOne(inversedBy: 'towns')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PositionGps $positionGps = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTownCode(): ?string
    {
        return $this->townCode;
    }

    public function setTownCode(string $townCode): static
    {
        $this->townCode = $townCode;

        return $this;
    }

    public function getTownName(): ?string
    {
        return $this->townName;
    }

    public function setTownName(string $townName): static
    {
        $this->townName = $townName;

        return $this;
    }

    public function getTownZipCode(): ?string
    {
        return $this->townZipCode;
    }

    public function setTownZipCode(string $townZipCode): static
    {
        $this->townZipCode = $townZipCode;

        return $this;
    }

    public function getPositionGps(): ?PositionGps
    {
        return $this->positionGps;
    }

    public function setPositionGps(?PositionGps $positionGps): static
    {
        $this->positionGps = $positionGps;

        return $this;
    }
}
