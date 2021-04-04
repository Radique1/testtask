<?php

namespace App\Builder;

use App\Car\Vehicle;
use App\Factory\Factory;
use App\Info\Logger;
use App\Parts\AdditionalOptions;
use App\Parts\Body;
use App\Parts\Color;
use App\Parts\Engine;
use App\Parts\Interior;
use App\Parts\Transmission;

class Builder
{
    private ?Vehicle $vehicle;
    private Logger $logger;

    public function __construct(?Vehicle $vehicle = null)
    {
        $this->vehicle = $vehicle;
        $this->logger = new Logger();
    }

    public function startBuilding(string $type): self
    {
        $this->vehicle = Factory::makeVehicle($type);

        return $this;
    }

    public function createBody(string $type, int $doorCount): self
    {
        $this->vehicle->body = new Body($type, $doorCount);
        $this->logger->logBuilderOperation($this->vehicle->body);

        return $this;
    }

    public function paint(string $type, string $color): self
    {
        $this->vehicle->color = new Color($type, $color);
        $this->logger->logBuilderOperation($this->vehicle->color);

        return $this;
    }

    public function putEngine(string $type, int $volume): self
    {
        $this->vehicle->engine = new Engine($type, $volume);
        $this->logger->logBuilderOperation($this->vehicle->engine);

        return $this;
    }

    public function makeInterior(string $type, string $color): self
    {
        $this->vehicle->interior = new Interior($type, $color);
        $this->logger->logBuilderOperation($this->vehicle->interior);

        return $this;
    }

    public function setTransmission(string $type, int $numberOfGears): self
    {
        $this->vehicle->transmission = new Transmission($type, $numberOfGears);
        $this->logger->logBuilderOperation($this->vehicle->transmission);

        return $this;
    }

    public function addOptions(array $options): self
    {
        $this->vehicle->additionalOptions = new AdditionalOptions();
        foreach ($options as $option) {
            $this->vehicle->additionalOptions->addOption($option);
        }
        $this->logger->logBuilderOperation($this->vehicle->additionalOptions);

        return $this;
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->vehicle;
    }
}