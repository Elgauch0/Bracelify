<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;

final class PublicController extends AbstractController
{




    #[Route('/', name: 'app_public')]
     public function index(): Response
    {
        return $this->render('public/index.html.twig');
    }





    #[Route('/products', name: 'app_public_products', methods: ['GET'])]
    public function products(Request $request, ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
     {
    $categoryId = $request->query->get('category');

    if ($categoryId) {
        $products = $productRepository->findAvailableProductsForCategory($categoryId);
    } else {
        $products = $productRepository->findAvailableProducts();
    }

    $categories = $categoryRepository->findAll();

    return $this->render('public/products.html.twig', [
        'products' => $products,
        'categories' => $categories,
        'currentCategory' => $categoryId,
    ]);
    }




    #[Route('/products/{id}', name: 'app_public_show', methods: ['GET'])]
    public function show(int $id, ProductRepository $productRepository, CommentRepository $commentRepository): Response
    {
        $product = $productRepository->find($id);
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        $comments = $commentRepository->getcommentsbyarticle($product);

        return $this->render('public/product.html.twig', [
            'product' => $product,
            'comments' => $comments,
        ]);
    }

}
