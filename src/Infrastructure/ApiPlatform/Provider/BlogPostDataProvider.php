<?php

namespace Blog\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use Blog\Application\Query\FindPostByUuidQuery;
use Blog\Domain\Exception\EntityNotFoundException;
use Blog\Domain\Model\BlogPost;
use Blog\Domain\Query\QueryBusInterface;
use Blog\Infrastructure\ApiPlatform\Resource\BlogPostResource;
use Blog\Infrastructure\ApiPlatform\Resource\BlogPostResourceFactory;
use Ramsey\Uuid\Uuid;

class BlogPostDataProvider implements DataProviderInterface
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

        if (!$operation instanceof Get) {
            return false;
        }

        return true;
    }

    /**
     * @param array<string, mixed> $uriVariables
     */
    public function provide(array $uriVariables): ?BlogPostResource
    {
        if (false === Uuid::isValid($uriVariables['uuid'])) {
            throw new \InvalidArgumentException('Please provide a valid uuid');
        }

        $post = $this->queryBus->ask(new FindPostByUuidQuery(Uuid::fromString($uriVariables['uuid'])));
        if (null === $post) {
            throw new EntityNotFoundException(BlogPost::class, $uriVariables['uuid'], 'uuid');
        }

        return $this->factory->fromModel($post);
    }
}
