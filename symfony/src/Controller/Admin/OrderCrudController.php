<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use App\Enum\OrderStatus;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(),
        
        // Champs en lecture seule (Désactivés lors de l'édition)
        DateTimeField::new('createdAt', 'Date de commande')
            ->setFormTypeOptions(['disabled' => true]),
            
        MoneyField::new('total', 'Montant Total')
            ->setCurrency('EUR')
            ->setStoredAsCents(true) // <--- C'est ICI que la magie opère
            ->setFormTypeOptions(['disabled' => true]), // Optionnel si tu veux formater en monnaie

        TextField::new('sessionStripe', 'ID Session Stripe')
            ->setFormTypeOptions(['disabled' => true])
            ->onlyOnDetail(), // On ne le montre que dans la page de détails

        // LE SEUL CHAMP MODIFIABLE
        ChoiceField::new('Status', 'Statut de la commande')
    ->setChoices([
        'En attente' => OrderStatus::PENDING,
        'Payée' => OrderStatus::PAID,
        'Annulée' => OrderStatus::CANCELLED,
        'Remboursée' => OrderStatus::REFUNDED,
        'Expédiée' => OrderStatus::SHIPPED,
    ])
    ->renderAsBadges([
        'PENDING'   => 'warning',   // Orange
        'PAID'      => 'success',   // Vert
        'CANCELLED' => 'danger',    // Rouge
        'REFUNDED'  => 'secondary', // Gris
        'SHIPPED'   => 'info',      // Bleu
    ]),

        // Pour voir les articles sans pouvoir les toucher
        CollectionField::new('items', 'Articles')
            ->onlyOnDetail() 
    ];
}
}
