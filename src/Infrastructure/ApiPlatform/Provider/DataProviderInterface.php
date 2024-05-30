<?php

namespace Blog\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\Operation;

interface DataProviderInterface
{
    public function supports(Operation $operation): bool;

    /**
     * @param array<string, mixed> $uriVariables
     */
    public function provide(array $uriVariables): mixed;
}
