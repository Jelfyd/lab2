<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\SpaceObject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<SpaceObject> */
class SpaceObjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceObject::class);
    }

    /** @return list<SpaceObject> */
    public function search(?string $term = null, ?int $categoryId = null): array
    {
        $queryBuilder = $this->createQueryBuilder('object')
            ->addSelect('category')
            ->join('object.category', 'category')
            ->orderBy('object.name', 'ASC');

        if ($categoryId !== null) {
            $queryBuilder
                ->andWhere('category.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }

        $objects = $queryBuilder->getQuery()->getResult();
        $term = trim((string) $term);

        if ($term === '') {
            return $objects;
        }

        // SQLite LIKE is only case-insensitive for ASCII; use Unicode-aware matching for Ukrainian text.
        return array_values(array_filter(
            $objects,
            static fn (SpaceObject $object): bool => mb_stripos($object->getName(), $term, 0, 'UTF-8') !== false
                || mb_stripos($object->getDescription(), $term, 0, 'UTF-8') !== false
                || mb_stripos($object->getCategory()?->getName() ?? '', $term, 0, 'UTF-8') !== false,
        ));
    }

    public function findOfTheDay(\DateTimeImmutable $date): ?SpaceObject
    {
        $objects = $this->findBy([], ['id' => 'ASC']);
        if ($objects === []) {
            return null;
        }

        $dayNumber = (int) $date->format('z');

        return $objects[$dayNumber % count($objects)];
    }

    public function createSearchQueryBuilder(string $term): QueryBuilder
    {
        return $this->createQueryBuilder('object')
            ->leftJoin('object.category', 'category')
            ->addSelect('category')
            ->andWhere('object.name LIKE :term OR object.description LIKE :term OR category.name LIKE :term')
            ->setParameter('term', '%'.$term.'%')
            ->orderBy('object.name', 'ASC');
    }
}
