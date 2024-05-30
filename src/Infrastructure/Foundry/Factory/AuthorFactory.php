<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Foundry\Factory;

use Blog\Domain\Model\Author;
use Ramsey\Uuid\Uuid;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<Author>
 *
 * @codeCoverageIgnore
 */
final class AuthorFactory extends ModelFactory
{
    /**
     * @return class-string<Author>
     */
    protected static function getClass(): string
    {
        return Author::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'id' => self::faker()->unique()->numberBetween(1, 1000000),
            'uuid' => Uuid::uuid4(),
            'name' => self::faker()->name(),
        ];
    }
}
