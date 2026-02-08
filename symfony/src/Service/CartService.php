<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class CartService
{
    public function __construct(
        private RequestStack $requestStack, private ProductRepository $productRepository,
    ) {
        // Accessing the session in the constructor is *NOT* recommended, since
        // it might not be accessible yet or lead to unwanted side-effects
        // $this->session = $requestStack->getSession();
    }

    public function addToCart(Product $product, int $quantity): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        $cart[$product->getId()] = ($cart[$product->getId()] ?? 0) + $quantity;
        $session->set('cart', $cart);
    }

    public function getCart(): array
    {
        $session = $this->requestStack->getSession();

        return $session->get('cart', []);
    }

    public function removeFromCart(int $productId): void
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
        }

        $session->set('cart', $cart);
    }

    public function clearCart(): void
    {
        $session = $this->requestStack->getSession();
        $session->remove('cart');
    }

    public function getFullCart(): array
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return ['items' => [], 'total' => 0];
        }

        $products = $this->productRepository->findBy(['id' => array_keys($cart)]);
        $total = 0;
        $FullCart = [];

        foreach ($products as $product) {
            $quantity = $cart[$product->getId()] ?? 0;
            $itemTotal = $product->getFinalPrice() * $quantity;
            $total += $itemTotal;

            $FullCart['items'][] = [
                'product' => $product,
                'quantity' => $quantity,
                'total' => $itemTotal,
            ];
        }

        return [
            'items' => $FullCart['items'] ?? [],
            'total' => $total,
        ];
    }


    
    public function addToCartOnlyIfnotadded(Product $product, int $quantity): bool
    {
        $session = $this->requestStack->getSession();
        $cart = $session->get('cart', []);
        if (!isset($cart[$product->getId()])) {
            $cart[$product->getId()] = $quantity;
            $session->set('cart', $cart);
            return true;
        }
        return false;
        
    }

}
