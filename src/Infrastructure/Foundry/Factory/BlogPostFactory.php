<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Foundry\Factory;

use Blog\Domain\Model\BlogPost;
use Ramsey\Uuid\Uuid;
use Zenstruck\Foundry\ModelFactory;

/**
 * @extends ModelFactory<BlogPost>
 *
 * @codeCoverageIgnore
 */
final class BlogPostFactory extends ModelFactory
{
    /**
     * @return class-string<BlogPost>
     */
    protected static function getClass(): string
    {
        return BlogPost::class;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getDefaults(): array
    {
        return [
            'id' => self::faker()->unique()->numberBetween(1, 1000000),
            'uuid' => Uuid::uuid4(),
            'title' => self::faker()->sentence(3),
            'content' => self::faker()->paragraphs(asText: true),
            'summary' => self::faker()->paragraph(2),
            'author' => AuthorFactory::createOne(),
            'createdAt' => $createdAt = self::faker()->dateTimeBetween('-2 years'),
            'updatedAt' => self::faker()->dateTimeBetween(startDate: $createdAt->format(\DateTimeInterface::ATOM)),
        ];
    }
}
