<?php

namespace App\Parts;

class Transmission implements BasePart
{
    private string $type;
    private ?int $numberOfGears;

    public function __construct($type, $numberOfGears)
    {
        $this->type = $type;
        $this->numberOfGears = $numberOfGears;
    }

    public function printCharacteristics(): string
    {
        return "Transmission type: {$this->type}, gears count: {$this->numberOfGears}";
    }
}