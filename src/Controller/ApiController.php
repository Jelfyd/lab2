<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\SpaceObject;
use App\Repository\SpaceObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

final class ApiController extends AbstractController
{
    #[Route('/api/space', name: 'api_space_index', methods: ['GET'])]
    public function index(SpaceObjectRepository $spaceObjects): JsonResponse
    {
        return $this->json([
            'data' => array_map(self::serializeObject(...), $spaceObjects->search()),
        ], 200, [], ['json_encode_options' => JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT]);
    }

    #[Route('/api/space/{id}', name: 'api_space_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id, SpaceObjectRepository $spaceObjects): JsonResponse
    {
        $spaceObject = $spaceObjects->find($id);
        if ($spaceObject === null) {
            return $this->json(['error' => 'Об’єкт не знайдено.'], 404, [], [
                'json_encode_options' => JSON_UNESCAPED_UNICODE,
            ]);
        }

        return $this->json([
            'data' => self::serializeObject($spaceObject),
        ], 200, [], ['json_encode_options' => JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT]);
    }

    /** @return array<string, int|float|string|null> */
    private static function serializeObject(SpaceObject $object): array
    {
        return [
            'id' => $object->getId(),
            'name' => $object->getName(),
            'type' => $object->getCategory()?->getName(),
            'description' => $object->getDescription(),
            'diameter_km' => $object->getDiameterKm(),
            'distance_from_sun_million_km' => $object->getDistanceFromSunMillionKm(),
            'fun_fact' => $object->getFunFact(),
        ];
    }
}
