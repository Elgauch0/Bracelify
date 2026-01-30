<?php

namespace App\Twig\Components;

use App\Entity\Product;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('product_card')]
class ProductCard
{
    public Product $product;
}
