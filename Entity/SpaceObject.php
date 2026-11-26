<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SpaceObjectRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpaceObjectRepository::class)]
#[ORM\Table(name: 'space_objects')]
#[ORM\Index(name: 'idx_space_object_category', columns: ['category_id'])]
class SpaceObject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 120)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 120)]
    private string $name = '';

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank]
    private string $description = '';

    #[ORM\Column(name: 'diameter_km', type: 'float', nullable: true)]
    #[Assert\Positive]
    private ?float $diameterKm = null;

    #[ORM\Column(name: 'distance_from_sun_million_km', type: 'float', nullable: true)]
    #[Assert\PositiveOrZero]
    private ?float $distanceFromSunMillionKm = null;

    #[ORM\Column(name: 'fun_fact', type: 'text')]
    #[Assert\NotBlank]
    private string $funFact = '';

    #[ORM\ManyToOne(targetEntity: SpaceCategory::class, inversedBy: 'spaceObjects')]
    #[ORM\JoinColumn(name: 'category_id', nullable: false, onDelete: 'RESTRICT')]
    #[Assert\NotNull]
    private ?SpaceCategory $category = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = trim($name);

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = trim($description);

        return $this;
    }

    public function getDiameterKm(): ?float
    {
        return $this->diameterKm;
    }

    public function setDiameterKm(?float $diameterKm): static
    {
        $this->diameterKm = $diameterKm;

        return $this;
    }

    public function getDistanceFromSunMillionKm(): ?float
    {
        return $this->distanceFromSunMillionKm;
    }

    public function setDistanceFromSunMillionKm(?float $distance): static
    {
        $this->distanceFromSunMillionKm = $distance;

        return $this;
    }

    public function getFunFact(): string
    {
        return $this->funFact;
    }

    public function setFunFact(string $funFact): static
    {
        $this->funFact = trim($funFact);

        return $this;
    }

    public function getCategory(): ?SpaceCategory
    {
        return $this->category;
    }

    public function setCategory(?SpaceCategory $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
