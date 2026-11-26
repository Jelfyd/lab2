<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\Admin\Crud\SpaceCategoryCrudController;
use App\Controller\Admin\Crud\SpaceObjectCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
final class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->redirectToRoute('admin_space_object_index');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Каталог космосу');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Головна', 'fa fa-home');
        yield MenuItem::section('Каталог');
        yield MenuItem::linkTo(SpaceObjectCrudController::class, 'Космічні об’єкти', 'fa fa-globe');
        yield MenuItem::linkTo(SpaceCategoryCrudController::class, 'Категорії', 'fa fa-tags');
        yield MenuItem::section('Навігація');
        yield MenuItem::linkToRoute('Перейти до каталогу', 'fa fa-external-link', 'site_index');
        yield MenuItem::linkToLogout('Вийти з адмінки', 'fa fa-sign-out');
    }
}
