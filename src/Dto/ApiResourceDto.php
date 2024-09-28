<?php

declare(strict_types = 1);

namespace App\Dto;

class ApiResourceDto
{
    /**
     * @var ApiOperationDto[]
     */
    private array $operations = [];

    private string $entityClassName;

    public function getOperation(string $key): ?ApiOperationDto
    {
        return $this->operations[$key] ?? null;
    }

    public function getOperations(): array
    {
        return $this->operations;
    }

    public function setOperations(array $operations): void
    {
        $this->operations = $operations;
    }

    public function addOperation(ApiOperationDto $operation, string $key): void
    {
        $this->operations[$key] = $operation;
    }

    public function getEntityClassName(): string
    {
        return $this->entityClassName;
    }

    public function setEntityClassName(string $entityClassName): void
    {
        $this->entityClassName = $entityClassName;
    }
}