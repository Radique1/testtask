<?php

namespace App\Manager;

use App\Builder\Builder;
use App\Car\Vehicle;
use App\Enum\AdminOperationEnum;
use App\Info\Logger;
use App\Info\Mailer;

class Manager
{
    private Builder $builder;
    private Logger $logger;
    private Mailer $mailer;

    public function __construct()
    {
        $this->logger = new Logger();
        $this->mailer = new Mailer();
    }

    public function setBuilder(Builder $builder): void
    {
        $this->builder = $builder;
    }

    public function handleOrder(array $order): void
    {
        $this->logger->logAdminOperation(AdminOperationEnum::ORDER_RECEIVED);
        $this->logger->logAdminOperation(AdminOperationEnum::ORDER_IN_PROCESS);
        $this->builder->startBuilding($order['carType'])
            ->createBody($order['body_type'], $order['body_doorCount'])
            ->paint($order['color_type'], $order['color_color'])
            ->putEngine($order['engine_type'], $order['engine_volume'])
            ->setTransmission($order['transmission_type'], $order['transmission_numberOfGears'])
            ->makeInterior($order['interior_type'], $order['interior_color'])
            ->addOptions($order['additionalOptions']);

        $this->logger->logAdminOperation(AdminOperationEnum::ORDER_COMPLETED);
        $this->mailer->sendEmail('Car is ready', '', $order['email'], $this->builder->getVehicle());
    }

    public function getVehicle(): ?Vehicle
    {
        return $this->builder->getVehicle();
    }
}