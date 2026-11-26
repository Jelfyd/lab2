<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Entity\SpaceCategory;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class SpaceCategoryCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SpaceCategory::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name', 'Назва категорії');
    }
}
