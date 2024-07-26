<?php

namespace App\Entity;

use App\Repository\DepartementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepartementRepository::class)]
class Departement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $depCode = null;

    #[ORM\Column(length: 255)]
    private ?string $depName = null;

    #[ORM\ManyToOne(inversedBy: 'departements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Region $region = null;

    #[ORM\OneToOne(inversedBy: 'capitalOfDepartement', cascade: ['persist', 'remove'])]
    private ?Town $capitalTown = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepCode(): ?string
    {
        return $this->depCode;
    }

    public function setDepCode(string $depCode): static
    {
        $this->depCode = $depCode;

        return $this;
    }

    public function getDepName(): ?string
    {
        return $this->depName;
    }

    public function setDepName(string $depName): static
    {
        $this->depName = $depName;

        return $this;
    }

    public function getRegion(): ?Region
    {
        return $this->region;
    }

    public function setRegion(?Region $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getCapitalTown(): ?Town
    {
        return $this->capitalTown;
    }

    public function setCapitalTown(?Town $capitalTown): static
    {
        $this->capitalTown = $capitalTown;

        // set (or unset) the owning side of the relation if necessary
        if ($capitalTown->getCapitalOfDepartement() !== $this) {
            $capitalTown->setCapitalOfDepartement($this);
        }

        return $this;
    }
}
