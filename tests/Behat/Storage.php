<?php

declare(strict_types=1);

namespace Blog\Tests\Behat;

use Blog\Domain\Model\BlogPost;
use Zenstruck\Foundry\Factory;
use Zenstruck\Foundry\Proxy;

final class Storage
{
    /**
     * @var array<class-string>
     */
    private array $storage = [];

    /**
     * @return Proxy<BlogPost>
     */
    public function getBlogPost(): Proxy
    {
        return $this->findEntity(BlogPost::class);
    }

    /**
     * @param Proxy<BlogPost> $post
     */
    public function setBlogPost(Proxy $post): void
    {
        $this->storeEntity($post);
    }

    /* @phpstan-ignore-next-line */
    private function storeEntity(Proxy $proxy): void
    {
        $class = get_class($proxy->object());
        /* @phpstan-ignore-next-line */
        $this->storage[$class] = $proxy;
    }

    /* @phpstan-ignore-next-line */
    private function findEntity(string $class): Proxy
    {
        if (null === $proxy = $this->storage[$class] ?? null) {
            throw new \RuntimeException(sprintf('Cannot find %s in memory storage', $class));
        }

        // Clear the proper object manager
        /* @phpstan-ignore-next-line */
        Factory::configuration()->objectManagerFor($proxy->object())->clear();

        /* @phpstan-ignore-next-line */
        return $proxy;
    }
}
