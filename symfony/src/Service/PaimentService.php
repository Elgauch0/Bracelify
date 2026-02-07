<?php

namespace App\Service;
use App\Service\OrderItemService;
use App\Service\CartService;
use App\Entity\Order;
use App\Entity\OrderItem;


class PaimentService
{

        public function __construct(
            private OrderItemService $orderItemService,
            private CartService $cartService,
        )
        {}


    /**@return OrderItem[] */
    public function createItemOrders(Order $order): array
    {
       $products = $this->cartService->getFullCart()['items'];
       $orderItems = [];
         foreach ($products as $product) {
          $orderItems[] = $this->orderItemService->createItemOrder($order, $product['product'], $product['quantity']);
         }

         return $orderItems;
    }


    public function addItemOrdersToOrder(Order $order, array $orderItems): void
    {
        foreach ($orderItems as $orderItem) {
            $order->addItem($orderItem);
        }
    }


}