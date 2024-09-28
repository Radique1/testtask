<?php

declare(strict_types = 1);

namespace App\Dto;

class ApiDto
{
    /**
     * @var ApiResourceDto[]
     */
    private array $resources = [];

    public function getResource(string $key): ?ApiResourceDto
    {
        return $this->resources[$key] ?? null;
    }

    public function getResources(): array
    {
        return $this->resources;
    }

    public function setResources(array $resources): void
    {
        $this->resources = $resources;
    }

    public function addResource(ApiResourceDto $resource, string $key): void
    {
        $this->resources[$key] = $resource;
    }
}