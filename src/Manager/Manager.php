<?php

namespace App\Manager;

use App\Builder\Builder;
use App\Car\Vehicle;
use App\Enum\AdminOperationEnum;
use App\Logger\LoggerTrait;

class Manager
{
    use LoggerTrait;

    private Builder $builder;

    public function setBuilder(Builder $builder): void
    {
        $this->builder = $builder;
    }

    public function handleOrder(array $order): void
    {
        $this->logger()->logAdminOperation(AdminOperationEnum::ORDER_RECEIVED);
        $this->logger()->logAdminOperation(AdminOperationEnum::ORDER_IN_PROCESS);
        $this->builder->startBuilding($order['carType'])
            ->createBody($order['body_type'], $order['body_doorCount'])
            ->paint($order['color_type'], $order['color_color'])
            ->putEngine($order['engine_type'], $order['engine_volume'])
            ->setTransmission($order['transmission_type'], $order['transmission_numberOfGears'])
            ->makeInterior($order['interior_type'], $order['interior_color'])
            ->addOptions($order['additionalOptions']);

        $this->logger()->logAdminOperation(AdminOperationEnum::ORDER_COMPLETED);
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->builder->getVehicle();
    }
}