<?php

namespace App\Info;


interface LoggerInterface
{
    public function info(string $content): void;
}