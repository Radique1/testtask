<?php

namespace App\Factory;

use App\Car\Car;
use App\Car\Vehicle;
use App\Car\Wagon;
use http\Exception\InvalidArgumentException;

class Factory
{
    static function makeVehicle(string $type): Vehicle
    {
        switch ($type) {
            case 'Car':
                return new Car;
            case 'Wagon':
                return new Wagon;
            default:
                throw new InvalidArgumentException('$type has incorrect value');
        }
    }
}