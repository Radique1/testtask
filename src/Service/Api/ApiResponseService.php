<?php

declare(strict_types = 1);

namespace App\Service\Api;

use ReflectionClass;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class ApiResponseService
{
    public function outputResponse(array $response, string $entityClassName, OutputInterface $output): void
    {
        $response = $this->formatResponse($response, $entityClassName);

        $table = new Table($output);

        $table->setHeaders(array_keys($response[0] ?? []));
        $table->setRows($response);
        $table->render();
    }

    private function formatResponse(array $response, string $entityClassName): array
    {
        $reflectionClass = new ReflectionClass($entityClassName);
        if (isset($response['hydra:member'])) {
            $result = [];

            foreach ($response['hydra:member'] as $value) {
                $result[] = $this->processItem($value, $reflectionClass);
            }

            return $result;
        }

        return [$this->processItem($response, $reflectionClass)];
    }

    private function processItem(array $item, ReflectionClass $reflectionClass): array
    {
        $result = [];

        foreach ($item as $key => $value) {
            if ($reflectionClass->hasProperty($key)) {
                $result[$key] = is_array($value) ? $value['id'] : $value;
            }
        }

        return $result;
    }
}