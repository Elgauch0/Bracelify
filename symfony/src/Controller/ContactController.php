<?php

namespace App\Controller;

use App\Form\ContactType;
use App\Service\MailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
#[Route('/contact', name: 'app_contact')]
public function index(Request $request, MailService $mailService): Response
{
    $form = $this->createForm(ContactType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        
        $data = $form->getData();

        try {
            
            $mailService->sendEmailToAdmin(
                $data['email'], 
                $data['subject'], 
                $data['message']
            );

            $this->addFlash('success', 'Message envoyé avec succès !');
            
            
            return $this->redirectToRoute('app_contact');

        } catch (\Exception $e) { 
            $this->addFlash('warning', 'Un problème est survenu lors de l\'envoi.');
        }
    }

    return $this->render('contact/index.html.twig', [
        'contactForm' => $form
    ]);
}
}
