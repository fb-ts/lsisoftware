<?php

namespace App\Entity;

use App\Repository\ExportHistoryRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;

#[ORM\Entity(repositoryClass: ExportHistoryRepository::class)]
#[HasLifecycleCallbacks]
class ExportHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private string $name;

    #[ORM\Column(name: 'created_at')]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(length: 255)]
    private string $userName;

    #[ORM\Column(name: 'location_name', length: 255)]
    private string $locationName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function prePersist(): static
    {
        if (!isset($this->createdAt)) {
            $this->createdAt = new DateTimeImmutable();
        }

        return $this;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt = null): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): static
    {
        $this->userName = $userName;

        return $this;
    }

    public function getLocationName(): string
    {
        return $this->locationName;
    }

    public function setLocationName(string $locationName): static
    {
        $this->locationName = $locationName;

        return $this;
    }
}
