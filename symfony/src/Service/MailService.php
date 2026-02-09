<?php


namespace App\Service;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



class MailService
{

    public function __construct(
        private readonly MailerInterface  $mailer,
        #[Autowire('%app.admin_email%')]
        private readonly string $adminEmail
    )
    {
    }


    public function sendEmailToUser(string $to,string $subject,string $message): void
    {


        $email = (new Email())
            ->from($this->adminEmail)
            ->to($to)
            ->subject($subject)
            ->html('<p>'.$message.'</p>');

        $this->mailer->send($email);

    }

    public function sendEmailToAdmin(string $from ,  string $subject, string $message): void
    {
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($this->adminEmail)
            ->replyTo($from)
            ->subject($subject)
            ->html('<p>'.$message.'</p>');

        $this->mailer->send($email);
    }



    // src/Service/MailService.php

public function sendStockAlert(array $errors): void
{
    $html = "<h1>⚠️ ALERTE : Survente détectée</h1>";
    $html .= "<p>Les articles suivants ont été payés mais n'étaient plus disponibles en stock :</p><ul>";
    
    foreach ($errors as $error) {
        $html .= sprintf(
            "<li><strong>%s</strong> : %d unité(s) manquante(s)</li>",
            $error['name'],
            $error['missing']
        );
    }
    
    $html .= "</ul><p>Veuillez contacter les clients pour un remboursement ou un délai supplémentaire.</p>";

    $this->sendEmailToAdmin($this->adminEmail, "CRITICAL: Problème de stock après paiement", $html);
}
}