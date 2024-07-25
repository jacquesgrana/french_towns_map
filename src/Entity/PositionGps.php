<?php

namespace App\Entity;

use App\Repository\PositionGpsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PositionGpsRepository::class)]
class PositionGps
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 10)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 13, scale: 10)]
    private ?string $longitude = null;

    /**
     * @var Collection<int, Town>
     */
    #[ORM\OneToMany(targetEntity: Town::class, mappedBy: 'positionGps')]
    private Collection $towns;

    public function __construct()
    {
        $this->towns = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * @return Collection<int, Town>
     */
    public function getTowns(): Collection
    {
        return $this->towns;
    }

    public function addTown(Town $town): static
    {
        if (!$this->towns->contains($town)) {
            $this->towns->add($town);
            $town->setPositionGps($this);
        }

        return $this;
    }

    public function removeTown(Town $town): static
    {
        if ($this->towns->removeElement($town)) {
            // set the owning side to null (unless already changed)
            if ($town->getPositionGps() === $this) {
                $town->setPositionGps(null);
            }
        }

        return $this;
    }
}
