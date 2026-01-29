<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\VichImageField;
use Vich\UploaderBundle\Form\Type\VichImageType;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use App\Form\ImageProductFormType;


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



    public function  configureFields(string $pageName):iterable
    {
        yield TextField::new( 'name','Nom du produit');
        yield TextEditorField::new('description') ->hideOnIndex();
        yield MoneyField::new( 'price','prix')->setCurrency('EUR');
        yield MoneyField::new( 'discount','réduction en euro')->setCurrency('EUR');
        yield AssociationField::new('category');
        yield CollectionField::new('productImages','image')
            ->setEntryType(ImageProductFormType::class);
    
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
