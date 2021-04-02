<?php

namespace App\Logger;


interface LoggerInterface
{
    public function info(string $string): void;
}