<?php

declare(strict_types=1);

namespace App\Controller\Admin\Crud;

use App\Entity\SpaceObject;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

final class SpaceObjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SpaceObject::class;
    }

    public function configureFields(string $pageName): iterable
    {
        yield IdField::new('id')->hideOnForm();
        yield TextField::new('name', 'Назва');
        yield AssociationField::new('category', 'Категорія');
        yield TextareaField::new('description', 'Опис');
        yield NumberField::new('diameterKm', 'Діаметр, км')->setNumDecimals(2);
        yield NumberField::new('distanceFromSunMillionKm', 'Відстань від Сонця, млн км')->setNumDecimals(2);
        yield TextareaField::new('funFact', 'Цікавий факт');
    }
}
