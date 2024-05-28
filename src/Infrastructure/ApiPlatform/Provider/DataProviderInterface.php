<?php

namespace Blog\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Operation;

interface DataProviderInterface
{
    public function supports(Operation $operation): bool;

    public function provide(): mixed;
}
