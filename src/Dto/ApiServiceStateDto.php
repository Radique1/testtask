<?php

declare(strict_types = 1);

namespace App\Dto;

class ApiServiceStateDto
{
    private ?string $resource = null;

    private ?string $action = null;

    private string $method;

    private string $routeName;

    private ?array $pathParameters = null;

    private ?array $payload = null;

    public function getResource(): ?string
    {
        return $this->resource;
    }

    public function setResource(?string $resource): void
    {
        $this->resource = $resource;
    }

    public function getAction(): ?string
    {
        return $this->action;
    }

    public function setAction(?string $action): void
    {
        $this->action = $action;
    }

    public function getRouteName(): ?string
    {
        return $this->routeName;
    }

    public function setRouteName(?string $routeName): void
    {
        $this->routeName = $routeName;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getPathParameters(): ?array
    {
        return $this->pathParameters;
    }

    public function setPathParameters(?array $pathParameters): void
    {
        $this->pathParameters = $pathParameters;
    }

    public function getPayload(): ?array
    {
        return $this->payload;
    }

    public function setPayload(?array $payload): void
    {
        $this->payload = $payload;
    }
}