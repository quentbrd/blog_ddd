<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Doctrine\Fixtures;

use Blog\Domain\Model\Author;
use Blog\Infrastructure\Foundry\Factory\AuthorFactory;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class AuthorFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        AuthorFactory::createMany(10);
    }

    /**
     * @return class-string
     */
    protected static function modelClass(): string
    {
        return Author::class;
    }
}
