<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Foundry\Factory;

use Blog\Domain\Model\Author;
use Faker\Generator;
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
        /** @var Generator $uniqueGenerator */
        $uniqueGenerator = self::faker()->unique();

        return [
            'id' => $uniqueGenerator->numberBetween(1, 1000000),
            'uuid' => Uuid::fromString($uniqueGenerator->uuid()),
            'name' => self::faker()->name(),
        ];
    }
}
