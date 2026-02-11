<?php


namespace App\Service;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\CommentRepository;
use App\Repository\OrderRepository;
class CommentService
{



public function __construct(
        private OrderRepository $orderRepository,
        private CommentRepository $commentRepository
    ) {}

public function isAllowed(?User $user, Product $product): bool
{
    if (!$user) {
        return false;
    }

    // 1. Est-ce qu'il a acheté le produit ?
    $hasPurchased = $this->orderRepository->hasPurchasedProduct($user, $product);
    
    // 2. Est-ce qu'il a déjà commenté ?
    $alreadyCommented = $this->commentRepository->hasAlreadyCommented($user, $product);

    // Il est autorisé seulement s'il a acheté ET n'a pas encore commenté
    return $hasPurchased && !$alreadyCommented;
}


}