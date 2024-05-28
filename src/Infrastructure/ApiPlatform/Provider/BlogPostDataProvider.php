<?php

namespace Blog\Infrastructure\ApiPlatform\Provider;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Operation;
use Blog\Infrastructure\ApiPlatform\Resource\BlogPostResource;
use Blog\Infrastructure\ApiPlatform\View\AuthorView;
use Ramsey\Uuid\Uuid;

class BlogPostDataProvider implements DataProviderInterface
{
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

    public function provide(): BlogPostResource
    {
        return new BlogPostResource(
            Uuid::uuid4(),
            'title',
            'content',
            'summary',
            new AuthorView('John Doe'),
            new \DateTimeImmutable(),
            new \DateTimeImmutable(),
        );
    }
}
