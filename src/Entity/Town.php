<?php

namespace App\Entity;

use App\Repository\TownRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToOne(mappedBy: 'capitalTown', cascade: ['persist', 'remove'])]
    private ?Region $capitalOfRegion = null;

    #[ORM\OneToOne(mappedBy: 'capitalTown', cascade: ['persist', 'remove'])]
    private ?Departement $capitalOfDepartement = null;

    #[ORM\ManyToOne(inversedBy: 'towns')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Departement $departement = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favoriteTowns')]
    private Collection $favoriteOfUsers;

    public function __construct()
    {
        $this->favoriteOfUsers = new ArrayCollection();
    }

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

    public function getCapitalOfRegion(): ?Region
    {
        return $this->capitalOfRegion;
    }

    public function setCapitalOfRegion(?Region $capitalOf): self
    {
        // unset the owning side of the relation if necessary
        if ($capitalOf === null && $this->capitalOf !== null) {
            $this->capitalOf->setCapitalTown(null);
        }

        // set the owning side of the relation if necessary
        if ($capitalOf !== null && $capitalOf->getCapitalTown() !== $this) {
            $capitalOf->setCapitalTown($this);
        }

        $this->capitalOf = $capitalOf;

        return $this;
    }

    public function getCapitalOfDepartement(): ?Departement
    {
        return $this->capitalOfDepartement;
    }

    public function setCapitalOfDepartement(?Departement $capitalOf): self
    {
        // unset the owning side of the relation if necessary
        if ($capitalOf === null && $this->capitalOf !== null) {
            $this->capitalOf->setCapitalTown(null);
        }

        // set the owning side of the relation if necessary
        if ($capitalOf !== null && $capitalOf->getCapitalTown() !== $this) {
            $capitalOf->setCapitalTown($this);
        }

        $this->capitalOf = $capitalOf;

        return $this;
    }

    public function getDepartement(): ?Departement
    {
        return $this->departement;
    }

    public function setDepartement(?Departement $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getFavoriteOfUsers(): Collection
    {
        return $this->favoriteOfUsers;
    }

    public function addFavoriteOfUser(User $favoriteOfUser): static
    {
        if (!$this->favoriteOfUsers->contains($favoriteOfUser)) {
            $this->favoriteOfUsers->add($favoriteOfUser);
            $favoriteOfUser->addFavoriteTown($this);
        }

        return $this;
    }

    public function removeFavoriteOfUser(User $favoriteOfUser): static
    {
        if ($this->favoriteOfUsers->removeElement($favoriteOfUser)) {
            $favoriteOfUser->removeFavoriteTown($this);
        }

        return $this;
    }
}
