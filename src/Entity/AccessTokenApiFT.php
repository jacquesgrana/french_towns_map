<?php

namespace App\Entity;

use App\Repository\AccessTokenApiFTRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccessTokenApiFTRepository::class)]
class AccessTokenApiFT
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column(type: Types::BIGINT)]
    private ?string $validUntilTS = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getValidUntilTS(): ?string
    {
        return $this->validUntilTS;
    }

    public function setValidUntilTS(string $validUntilTS): static
    {
        $this->validUntilTS = $validUntilTS;

        return $this;
    }
}
