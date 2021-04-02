<?php

namespace App\Parts;

class Color implements BasePart
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
        return "Paint type: {$this->type}, color: {$this->color}";
    }
}