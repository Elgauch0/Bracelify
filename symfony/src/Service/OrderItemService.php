<?php


namespace App\Service;

use App\Entity\Product;
use App\Entity\OrderItem;


class OrderItemService
{
    public function createItemOrder(Order $order, Product $product, int $quantity): OrderItem
    {
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setProduct($product);
        $orderItem->setPrice($product->getPrice());
        $orderItem->setQuantity($quantity);
        

        return $orderItem;
    }
     
}