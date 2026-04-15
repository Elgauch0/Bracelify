<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Product;
use App\Form\CommentType;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\ProductRepository;
use App\Service\CommentService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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

    #[Route('/products/{id}', name: 'app_public_show', methods: ['GET', 'POST'])]
    public function show(Product $product, Request $request, CommentRepository $commentRepository, CommentService $commentService, EntityManagerInterface $em): Response
    {
        if (!$product) {
            throw $this->createNotFoundException('Product not found');
        }

        /** @var User user */
        $user = $this->getUser();
        $isAllowedToComment = $commentService->isAllowed($user, $product);
        $comments = $commentRepository->getcommentsbyarticle($product);
        $form = null;
        if ($isAllowedToComment) {
            $comment = new Comment();
            $form = $this->createForm(CommentType::class, $comment);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $comment->setProduct($product);
                $comment->setAuthor($user);
                $comment->setCreatedAt(new \DateTimeImmutable());
                $em->persist($comment);
                $em->flush();
            }
        }

        return $this->render('public/product.html.twig', [
            'product' => $product,
            'comments' => $comments,
            'form' => $form,
        ]);
    }

    #[Route('/about', name: 'app_public_about', methods: ['GET'])]
    public function about(): Response
    {
        return $this->render('public/about.html.twig');
    }
}
