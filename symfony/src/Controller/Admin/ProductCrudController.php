<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use App\Form\ImageProductFormType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setPageTitle('new', 'Créer un produit :')
            ->setPageTitle('edit', 'Modifier un produit :')
            ->setPageTitle('index', 'Liste des produits :')
            ->setPageTitle('detail', 'Détails du produit :');
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('name', 'Nom du produit');
        yield TextField::new('description')->hideOnIndex();
        yield IntegerField::new('quantity')->setLabel('Quantité en stock');
        yield MoneyField::new('price', 'prix')->setCurrency('EUR');
        yield MoneyField::new('discount', 'réduction en euro')->setCurrency('EUR');
        yield AssociationField::new('category');
        yield AssociationField::new('productCollections', 'Collections associées')
            ->setFormTypeOption('by_reference', false)
            ->hideOnIndex();        yield CollectionField::new('productImages', 'image')
            ->setEntryType(ImageProductFormType::class)->hideOnIndex();
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
