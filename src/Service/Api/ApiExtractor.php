<?php

declare(strict_types = 1);

namespace App\Service\Api;

use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Exception\ResourceClassNotFoundException;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\HttpOperation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Resource\Factory\ResourceMetadataCollectionFactoryInterface;
use ApiPlatform\Metadata\Resource\Factory\ResourceNameCollectionFactoryInterface;
use App\Dto\ApiDto;
use App\Dto\ApiOperationDto;
use App\Dto\ApiResourceDto;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;

class ApiExtractor
{
    public function __construct(
        private readonly ResourceMetadataCollectionFactoryInterface $resourceMetadataCollectionFactory,
        private readonly ResourceNameCollectionFactoryInterface $resourceNameCollectionFactory,
    ) {
    }

    public function extract(): ApiDto
    {
        $apiDto = new ApiDto();

        $resources = $this->resourceNameCollectionFactory->create();
        foreach ($resources as $resource) {
            if (!str_contains($resource, ApiService::API_RESOURCE_NAMESPACE)) {
                continue;
            }

            try {
                $metadata = $this->resourceMetadataCollectionFactory->create($resource);
            } catch (ResourceClassNotFoundException) {
                continue;
            }

            $apiResource = $metadata->getIterator()->current();
            $apiResourceDto = new ApiResourceDto();
            $apiResourceDto->setEntityClassName($resource);
            foreach ($apiResource->getOperations() as $operation) {
                $operationDto = $this->extractOperation($operation);
                $apiResourceDto->addOperation($operationDto, $operationDto->getName());
            }

            $apiDto->addResource($apiResourceDto, $this->getResourceName($resource));
        }

        return $apiDto;
    }

    private function getResourceName(string $entityClass): string
    {
        $parts = explode('\\', $entityClass);

        return end($parts);
    }

    private function extractOperation(HttpOperation $operation): ApiOperationDto
    {
        $operationDto = new ApiOperationDto();

        $operationDto->setName($this->getName($operation));
        $operationDto->setRouteName($operation->getName());
        $operationDto->setMethod($operation->getMethod());
        $operationDto->setPathParameters($this->extractPathParameters($operation));
        $operationDto->setFields($this->extractFields($operation));

        return $operationDto;
    }

    private function getName(HttpOperation $operation): string
    {
        return match (true) {
            (bool) $operation->getDescription() => $operation->getDescription(),
            $operation instanceof GetCollection => 'List',
            $operation instanceof Get => 'Read',
            $operation instanceof Patch => 'Update',
            $operation instanceof Post => 'Create',
            $operation instanceof Delete => 'Delete',
            default => $operation->getName(),
        };
    }

    private function extractPathParameters(HttpOperation $operation): array
    {
        $pathParameters = [];

        if (!$operation->getUriVariables()) {
            return $pathParameters;
        }

        foreach ($operation->getUriVariables() as $key => $value) {
            $pathParameters[] = $key;
        }

        return $pathParameters;
    }

    /**
     * @throws ReflectionException
     */
    private function extractFields(HttpOperation $operation): array
    {
        $fields = [];

        if (!$operation instanceof Post && !$operation instanceof Patch) {
            return $fields;
        }

        $reflectionClass = new ReflectionClass($operation->getClass());
        $denormalizationGroups = $operation->getDenormalizationContext()[AbstractNormalizer::GROUPS] ?? [];
        foreach ($reflectionClass->getProperties() as $reflectionProperty) {
            $attributes = $reflectionProperty->getAttributes(Groups::class);

            if (!$attributes) {
                continue;
            }

            $propertyGroups = $attributes[0]->getArguments();
            $propertyGroups = $propertyGroups[0] ?? $propertyGroups[AbstractNormalizer::GROUPS];
            if (array_intersect($denormalizationGroups, $propertyGroups)) {
                $fields[] = $reflectionProperty->getName();
            }
        }

        return $fields;
    }
}