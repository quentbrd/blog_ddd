<?php

namespace Blog\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Operation;
use Blog\Application\Query\FindPostsQuery;
use Blog\Domain\Query\QueryBusInterface;
use Blog\Infrastructure\ApiPlatform\Resource\BlogPostResource;
use Blog\Infrastructure\ApiPlatform\Resource\BlogPostResourceFactory;

class BlogPostCollectionDataProvider implements DataProviderInterface
{
    public function __construct(
        private QueryBusInterface $queryBus,
        private BlogPostResourceFactory $factory,
    ) {
    }

    public function supports(Operation $operation): bool
    {
        if (BlogPostResource::class !== $operation->getClass()) {
            return false;
        }

        if (!$operation instanceof GetCollection) {
            return false;
        }

        return true;
    }

    /**
     * @param array<string, mixed> $uriVariables
     *
     * @return array<BlogPostResource>
     */
    public function provide(array $uriVariables): array
    {
        return array_map(
            [$this->factory, 'fromModel'],
            $this->queryBus->ask(new FindPostsQuery())
        );
    }
}
