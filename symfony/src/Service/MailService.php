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

    public function sendEmailToAdmin(string $from,  string $subject, string $message): void
    {
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($this->adminEmail)
            ->replyTo($from)
            ->subject($subject)
            ->html('<p>'.$message.'</p>');

        $this->mailer->send($email);
    }
}