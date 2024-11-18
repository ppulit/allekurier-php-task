<?php

namespace App\Common\Mailer;

class SMPTMailer implements MailerInterface
{
    public function send(string $recipient, string $subject, string $message): void
    {
        // mail został wysłany
        echo 'Wysłano e-mail: '.$recipient.' '.$subject.' '.$message.PHP_EOL;
    }
}
