<?php

namespace App\Parts;

class AdditionalOptions implements BasePart
{
    private array $options = [];

    public function addOption(string $option): void
    {
        if (!in_array($option, $this->options)) {
            $this->options[] = $option;
        }
    }

    public function printCharacteristics(): string
    {
        $characteristics = 'Additional Options: ';

        if (!count($this->options)) {
            $characteristics .= 'there is no additional options';
        } else {
            $characteristics .= implode(', ', $this->options);
        }

        return $characteristics;
    }
}