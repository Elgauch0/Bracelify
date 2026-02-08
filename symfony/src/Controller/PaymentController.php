<?php

namespace App\Controller;

use App\Entity\Order;
use App\Enum\OrderStatus;
use \Stripe\StripeClient;
use App\Service\CartService;
use App\Service\PaimentService;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;




#[Route('/payment')]
final class PaymentController extends AbstractController
{
  
    public function __construct(
        #[Autowire(env: 'STRIPE_SECRET_KEY')]
        private readonly string $STRIPE_SECRET_KEY,
        
    )
    {}


    #[Route('/checkout', name: 'app_payment_checkout')]
    public function checkout(PaimentService $paimentService,EntityManagerInterface $entityManager,CartService $cartService): RedirectResponse
    {


        
        #preparin Strip checkout session   
        $order = new Order();
        $itemOrders = $paimentService->createItemOrders($order);
        $paimentService->addItemOrdersToOrder($order, $itemOrders);
        $order->recalculateTotal();
        
        $entityManager->persist($order);
        $entityManager->flush();


      $lineItems = [];
    foreach ($cartService->getFullCart()['items'] as $item) {
        $lineItems[] = [
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => $item['product']->getName(),
                ],
                'unit_amount' => $item['product']->getFinalPrice(), // Déjà en centimes 
            ],
            'quantity' => $item['quantity'],
        ];
    }

    $stripe = new StripeClient($this->STRIPE_SECRET_KEY);
    $checkoutSession = $stripe->checkout->sessions->create([
        'payment_method_types' => ['card'],
        'line_items' => $lineItems,
        'mode' => 'payment',
        // CYBER REFLEX : On lie l'ID de notre base à la session Stripe
        'metadata' => [
            'order_id' => $order->getId() 
        ],
        'success_url' => $this->generateUrl('app_payment_success', [], UrlGeneratorInterface::ABSOLUTE_URL).'?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => $this->generateUrl('app_payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL),
    ]);
        
        $order->setSessionStripe($checkoutSession->id);
        $entityManager->persist($order);
        $entityManager->flush();
      
        return $this->redirect($checkoutSession->url,303);
    }





    #[Route('/success', name: 'app_payment_success')]
   public function success(Request $request,OrderRepository $orderRepo,EntityManagerInterface $em,CartService $cartService): Response {
    $sessionId = $request->query->get('session_id');

    if (!$sessionId) {
        return $this->redirectToRoute('app_public');
    }

    
    $order = $orderRepo->findOneBy(['sessionStripe' => $sessionId]);

    
    if ($order && $order->getStatus() === OrderStatus::PENDING) {
        $order->setStatus(OrderStatus::PAID);
        $em->flush();
        $cartService->clearCart();
    }

    return $this->render('payment/success.html.twig', [
        'order' => $order
    ]);
}





    #[Route('/cancel', name: 'app_payment_cancel')]
    public function cancel(): Response
    {
        // Logique d'annulation de paiement ici

        // Affiche une page d'annulation de paiement
        return $this->render('payment/cancel.html.twig');
    }
}
