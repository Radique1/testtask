<?php

namespace App\Car;

use App\Parts\AdditionalOptions;
use App\Parts\Body;
use App\Parts\Color;
use App\Parts\Engine;
use App\Parts\Interior;
use App\Parts\Transmission;

abstract class Vehicle
{
    public int $wheelCount;
    public Body $body;
    public Color $color;
    public Engine $engine;
    public Interior $interior;
    public Transmission $transmission;
    public AdditionalOptions $additionalOptions;

    public function printCharacteristics(): string
    {
        $properties = get_object_vars($this);
        $characteristics = '';

        foreach ($properties as $property => $value) {
            if (is_object($value)) {
                $characteristics .= $this->$property->printCharacteristics() . '<br>';
            } else {
                $characteristics .= ucfirst($property) . ": {$this->$property}<br>";
            }
        }

        return $characteristics;
    }
}