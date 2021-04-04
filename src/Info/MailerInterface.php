<?php

namespace App\Info;


interface MailerInterface
{
    public function sendEmail(string $subject, string $content, string $email): void;

    public function send(object $email): void;
}