<?php

namespace App\Logger;

trait LoggerTrait
{
    public function logger()
    {
        return new Logger();
    }
}