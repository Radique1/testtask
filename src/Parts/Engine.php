<?php

namespace App\Parts;

class Engine implements BasePart
{
    private string $type;
    private int $volume;

    public function __construct(string $type, int $volume)
    {
        $this->type = $type;
        $this->volume = $volume;
    }

    public function printCharacteristics(): string
    {
        return "Engine type: {$this->type}, engine volume: {$this->volume}";
    }
}