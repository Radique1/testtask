<?php

namespace App\Info;


class Mailer implements MailerInterface
{

    public function composeEmailCarReady(object $object): string
    {
        return "Hello! Your car is ready. \n" . $object->printCharacteristics();
    }

    public function sendEmail(string $subject, string $content, string $emailAddress, ?object $object = null): void
    {
        $email = new \stdClass();
        $email->from = 'test@test.test';
        $email->to = $emailAddress;
        $email->subject = $subject;
        $email->content = $object ? $this->composeEmailCarReady($object) : $content;

        $this->send($email);
    }

    public function send(object $email): void
    {
        echo 'sent';
    }

}