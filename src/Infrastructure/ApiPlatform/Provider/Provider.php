<?php

namespace Blog\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

/**
 * @implements ProviderInterface<object>
 */
final readonly class Provider implements ProviderInterface
{
    /**
     * @param \Traversable<DataProviderInterface> $dataProviders
     */
    public function __construct(private \Traversable $dataProviders)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        foreach ($this->dataProviders as $dataProvider) {
            if (true === $dataProvider->supports($operation)) {
                return $dataProvider->provide($uriVariables);
            }
        }

        return null;
    }
}
