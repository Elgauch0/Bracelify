<?php


namespace App\Service;

use App\Entity\Product;
use App\Entity\OrderItem;
use App\Entity\Order;


class OrderItemService
{
    public function createItemOrder(Order $order, Product $product, int $quantity=1): OrderItem
    {
        $orderItem = new OrderItem();
        $orderItem->setOrder($order);
        $orderItem->setProduct($product);
        $orderItem->setPrice($product->getPrice());
        $orderItem->setQuantity($quantity);
        

        return $orderItem;
    }
     
}