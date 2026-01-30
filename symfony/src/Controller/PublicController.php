<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;

final class PublicController extends AbstractController
{




    #[Route('/', name: 'app_public')]
    public function index(): Response
    {
        return $this->render('public/index.html.twig');
    }





    #[Route('/products', name: 'app_public_products', methods: ['GET'])]
    public function products(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();
        $categories = $productRepository->findAllCategories();
        
        return $this->render('public/products.html.twig', [
            'products' => $products,
            'categories' => $categories,
        ]);
    }



    #[Route('/products/{id}', name: 'app_public_show', methods: ['GET'])]
    public function show(int $id, ProductRepository $productRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }
        return $this->render('public/product.html.twig', [
            'product' => $product,
        ]);
    }

}
