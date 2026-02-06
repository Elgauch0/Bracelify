<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/cart')]
final class CartController extends AbstractController
{
    public function __construct(
        private CartService $cartService,
    ) {
    }

    #[Route('/', name: 'app_cart_index')]
    public function index(): Response
    {
        $FullCart = $this->cartService->getFullCart();

        return $this->render('cart/index.html.twig', [
            'cart' => $FullCart,
        ]);
    }

    #[Route('/add/{id}', name: 'app_cart_add', methods: ['POST'], requirements: ['id' => Requirement::POSITIVE_INT, 'quantity' => Requirement::POSITIVE_INT])]
    public function addToCart(Product $product, Request $request): Response
    {
        // Vérification du token CSRF
        $token = $request->request->get('_token');

        if (!$this->isCsrfTokenValid('add'.$product->getId(), $token)) {
            $this->addFlash('danger', 'Votre session a expiré ou la requête est invalide. Veuillez réessayer.');

            return $this->redirectToRoute('app_cart_index');
        }
        $this->cartService->addToCart($product, 1);
        $this->addFlash('success', 'Produit ajouté au panier !');

        $referer = $request->headers->get('referer');

        return $this->redirect($referer ?: $this->generateUrl('app_cart_index'));
    }

    #[Route('/remove/{id}', name: 'app_cart_remove', methods: ['POST'], requirements: ['id' => Requirement::POSITIVE_INT])]
    public function removeFromCart(int $id, Request $request): Response
    {
        $token = $request->request->get('_token');

        if (!$this->isCsrfTokenValid('remove'.$id, $token)) {
            $this->addFlash('danger', 'Votre session a expiré ou la requête est invalide. Veuillez réessayer.');

            return $this->redirectToRoute('app_cart_index');
        }
        $this->cartService->removeFromCart($id);
        $this->addFlash('success', 'Produit supprimé du panier !');

        return $this->redirectToRoute('app_cart_index');
    }
}
// $cart =[ 1 => 3,3 => 1]
