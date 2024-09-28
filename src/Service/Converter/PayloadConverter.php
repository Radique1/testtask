<?php

declare(strict_types = 1);

namespace App\Service\Converter;

use ApiPlatform\Api\IriConverterInterface;
use App\Service\Api\ApiService;
use ReflectionClass;

class PayloadConverter
{
    public function __construct(
        private readonly IriConverterInterface $iriConverter,
    ) {
    }

    public function convert(array $payload, string $entityClassName): array
    {
        $reflectionClass = new ReflectionClass($entityClassName);
        foreach ($payload as $key => $value) {
            $reflectionProperty = $reflectionClass->getProperty($key);
            if (str_contains($reflectionProperty->getType()->getName(), ApiService::API_RESOURCE_NAMESPACE)) {
                $payload[$key] = $this->iriConverter->getIriFromResource(
                    resource: $reflectionProperty->getType()->getName(),
                    context: ['uri_variables' => ['id' => $value]],
                );
            }
        }

        return $payload;
    }
}