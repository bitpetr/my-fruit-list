<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class FruitDatasetUpdateNotifier
{
    public function __construct(
        private MailerInterface $mailer,
        private string $adminEmail
    ) {
    }

    public function notify(array $new, array $updated): void
    {
        $email = (new Email())
            ->from($this->adminEmail)
            ->to($this->adminEmail)
            ->subject('Fruits database updated')
            ->text($this->buildEmailMessage($new, $updated));

        $this->mailer->send($email);
    }

    private function buildEmailMessage(array $new, array $updated): string
    {
        $message = count($new) . " new fruit(s) have been added to the database:\n\n";

        foreach ($new as $fruit) {
            $message .= "- {$fruit->getName()} ({$fruit->getFamily()})\n";
        }
        $message .= count($updated) . " existing fruit(s) have been updated:\n\n";

        foreach ($updated as $fruit) {
            $message .= "- {$fruit->getName()} ({$fruit->getFamily()})\n";
        }

        return $message;
    }
}