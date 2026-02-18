<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }


    public function configureActions(Actions $actions): Actions
    {
        return $actions
            // Désactive totalement la création de nouveaux commentaires
            ->disable(Action::NEW);
           
    }

    public function configureFields(string $pageName): iterable
{
    return [
        // On affiche l'auteur et le produit, mais on empêche toute modfication
        AssociationField::new('author', 'Auteur')
            ->setDisabled(true), 
            
        AssociationField::new('product', 'Produit')
            ->setDisabled(true),

        // Pour le contenu, on peut le laisser lisible mais non modifiable
        TextEditorField::new('content', 'Contenu')
            ->setDisabled(true),

        // Lui reste totalement interactif
        BooleanField::new('isValid', 'Valide')
            ->renderAsSwitch(true),
    ];
}
}
