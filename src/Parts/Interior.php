<?php

namespace App\Parts;

class Interior implements BasePart
{
    private string $type;
    private string $color;

    public function __construct($type, $color)
    {
        $this->type = $type;
        $this->color = $color;
    }

    public function printCharacteristics(): string
    {
        return "Interior type: {$this->type}, interior color: {$this->color}";
    }
}