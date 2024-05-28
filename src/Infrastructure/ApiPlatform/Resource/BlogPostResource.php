<?php

namespace Blog\Infrastructure\ApiPlatform\Resource;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use Blog\Infrastructure\ApiPlatform\Provider\Provider;
use Blog\Infrastructure\ApiPlatform\View\AuthorView;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    shortName: 'BlogPost',
    operations: [
        new Get(
            uriTemplate: '/posts/{uuid}',
            normalizationContext: ['groups' => ['post:item']],
        ),
        new GetCollection(
            uriTemplate: '/posts',
            normalizationContext: ['groups' => ['post:list']],
        ),
    ],
    provider: Provider::class,
)]
class BlogPostResource
{
    public function __construct(
        #[ApiProperty(identifier: true)]
        #[Groups(['post:item', 'post:list'])]
        public UuidInterface $uuid,
        #[Groups(['post:item', 'post:list'])]
        public string $title,
        #[Groups(['post:item'])]
        public string $content,
        #[Groups(['post:item', 'post:list'])]
        public string $summary,
        #[Groups(['post:item', 'post:list'])]
        public AuthorView $author,
        #[Groups(['post:item', 'post:list'])]
        public \DateTimeInterface $createdAt,
        #[Groups(['post:item', 'post:list'])]
        public \DateTimeInterface $updatedAt,
    ) {
    }
}
