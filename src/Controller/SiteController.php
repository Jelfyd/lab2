<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\SpaceCategoryRepository;
use App\Repository\SpaceObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SiteController extends AbstractController
{
    #[Route('/', name: 'site_index', methods: ['GET'])]
    public function index(
        Request $request,
        SpaceCategoryRepository $categories,
        SpaceObjectRepository $spaceObjects,
    ): Response
    {
        $search = trim((string) $request->query->get('q', ''));
        $categoryId = $request->query->getInt('category');
        $categoryId = $categoryId > 0 ? $categoryId : null;
        $categoryOptions = $categories->findBy([], ['name' => 'ASC']);

        if ($categoryId !== null && $categories->find($categoryId) === null) {
            $categoryId = null;
        }

        return $this->render('site/index.html.twig', [
            'objects' => $spaceObjects->search($search, $categoryId),
            'categories' => $categoryOptions,
            'totalObjectCount' => $spaceObjects->count([]),
            'selectedCategoryId' => $categoryId,
            'dailyObject' => $spaceObjects->findOfTheDay(new \DateTimeImmutable('today')),
            'search' => $search,
        ]);
    }

    #[Route('/space/{id}', name: 'site_show', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function show(int $id, SpaceObjectRepository $spaceObjects): Response
    {
        $spaceObject = $spaceObjects->find($id);
        if ($spaceObject === null) {
            throw $this->createNotFoundException('Космічний об’єкт не знайдено.');
        }

        return $this->render('site/show.html.twig', [
            'object' => $spaceObject,
        ]);
    }
}
