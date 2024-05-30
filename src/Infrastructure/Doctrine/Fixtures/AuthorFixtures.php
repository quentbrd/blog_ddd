<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Doctrine\Fixtures;

use Blog\Domain\Model\Author;
use Doctrine\Persistence\ObjectManager;

/**
 * @codeCoverageIgnore
 */
class AuthorFixtures extends AbstractFixture
{
    public function load(ObjectManager $manager): void
    {
        // authors are created with blog posts
    }

    /**
     * @return class-string
     */
    protected static function modelClass(): string
    {
        return Author::class;
    }
}
