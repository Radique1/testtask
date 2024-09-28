<?php

declare(strict_types = 1);

namespace App\DataProvider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Group;

class GroupReportDataProvider implements ProviderInterface
{
    public function __construct(
        protected readonly ProviderInterface $itemProvider,
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        /** @var Group $group */
        $group = $this->itemProvider->provide($operation, $uriVariables, $context);

        return $group->getUsers()->toArray();
    }
}