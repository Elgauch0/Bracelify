<?php

namespace App\Tests\Entity;

use App\Entity\Category;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class ProductTest extends TestCase
{
    public function testProductEntity(): void
    {
        $category = new Category();
        $category->setLabel('Test Category');

        $product = new Product();
        $product->setName('Test Product');
        $product->setDescription('This is a test product.');
        $product->setPrice(100);
        $product->setCategory($category);
        $product->setDiscount(10);
        $product->setQuantity(5);

        $this->assertEquals('Test Product', $product->getName());
        $this->assertEquals('This is a test product.', $product->getDescription());
        $this->assertEquals(100, $product->getPrice());
        $this->assertEquals(10, $product->getDiscount());
        $this->assertEquals(5, $product->getQuantity());
        $this->assertEquals($category, $product->getCategory());
        $this->assertEquals(90, $product->getfinalPrice());
    }

    public function testUserEntity(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');

        $user->setPassword('hashedpassword');
        $user->setFirstname('John');
        $user->setLastname('Doe');
        $user->setAdress('123 Test Street');

        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('hashedpassword', $user->getPassword());
        $this->assertEquals('John', $user->getFirstname());
        $this->assertEquals('Doe', $user->getLastname());
        $this->assertEquals('123 Test Street', $user->getAdress());

        $order = new Order();
        $order->setTotal(200);
        $order->setClient($user);
        $this->assertEquals(200, $order->getTotal());
        $this->assertEquals($user, $order->getClient());
    }
}
