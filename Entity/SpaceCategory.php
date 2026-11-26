<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SpaceCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpaceCategoryRepository::class)]
#[ORM\Table(name: 'space_categories')]
#[ORM\UniqueConstraint(name: 'uniq_space_category_name', columns: ['name'])]
class SpaceCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 80)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 80)]
    private string $name = '';

    /** @var Collection<int, SpaceObject> */
    #[ORM\OneToMany(targetEntity: SpaceObject::class, mappedBy: 'category')]
    #[ORM\OrderBy(['name' => 'ASC'])]
    private Collection $spaceObjects;

    public function __construct()
    {
        $this->spaceObjects = new ArrayCollection();
    }

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

    /** @return Collection<int, SpaceObject> */
    public function getSpaceObjects(): Collection
    {
        return $this->spaceObjects;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
