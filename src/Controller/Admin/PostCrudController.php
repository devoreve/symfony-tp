<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort([
            'featured' => 'DESC',
            'createdAt' => 'DESC'
        ]);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title'),
            TextareaField::new('content'),
            BooleanField::new('premium'),
            MoneyField::new('price')
                ->setCurrency('EUR')
                ->setStoredAsCents(),
            BooleanField::new('featured'),
            DateTimeField::new('createdAt')->hideOnForm(),
            AssociationField::new('category')->autocomplete(),
            AssociationField::new('tags')
                ->autocomplete()
                ->setFormTypeOption('by_reference', false),
        ];
    }
}
