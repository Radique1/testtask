<?php

namespace App\Parts;

class Body implements BasePart
{
    private string $type;
    private int $doorCount;

    public function __construct($type, $doorCount)
    {
        $this->type = $type;
        $this->doorCount = $doorCount;
    }

    public function printCharacteristics(): string
    {
        return "Body type: {$this->type}, door count: {$this->doorCount}";
    }
}