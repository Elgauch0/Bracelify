<?php

namespace App\Controller;

use App\Form\UserType;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

final class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil', methods: ['GET'])]
    public function index(OrderRepository $orderRepository): Response
    {
        $user = $this->getUser();
        $orders = $orderRepository->findBy(['client' => $user], ['createdAt' => 'DESC']);

        return $this->render('profil/index.html.twig', [
            'controller_name' => 'ProfilController',
            'orders' => $orders,
        ]);
    }

    #[Route('/profil/edit', name: 'app_profil_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        /* @var App\Entity\User $user */
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // If a new password is provided, encode and set it
            $plainPassword = $form->get('plainPassword')->getData();
            if ($plainPassword) {
                $encodedPassword = $passwordHasher->hashPassword($user, $plainPassword);
                $user->setPassword($encodedPassword);
            }

            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully.');

            return $this->redirectToRoute('app_profil');
        }

        return $this->render('profil/edit.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/profil/delete', name: 'app_profil_delete', methods: ['DELETE'])]
    public function delete(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->addFlash('warning', 'Admin profiles cannot be deleted.');

            return $this->redirectToRoute('app_profil');
        }

        // Vérification du jeton CSRF pour la sécurité
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            // Logique de déconnexion manuelle avant suppression
            $this->container->get('security.token_storage')->setToken(null);
            $request->getSession()->invalidate();

            $entityManager->remove($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été supprimé conformément au RGPD.');

            return $this->redirectToRoute('app_public');
        }

        $this->addFlash('danger', 'Jeton de sécurité invalide.');

        return $this->redirectToRoute('app_profil_edit');
    }
}
