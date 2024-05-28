<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Doctrine\Fixtures;

use Blog\Domain\Model\BlogPost;
use Blog\Infrastructure\Foundry\Factory\BlogPostFactory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class BlogPostFixtures extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        BlogPostFactory::createMany(10);
    }

    /**
     * @return class-string
     */
    protected static function modelClass(): string
    {
        return BlogPost::class;
    }

    public function getDependencies(): array
    {
        return [
            AuthorFixtures::class,
        ];
    }
}
