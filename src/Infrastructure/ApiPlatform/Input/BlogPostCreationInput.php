<?php

namespace Blog\Infrastructure\ApiPlatform\Input;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class BlogPostCreationInput
{
    public function __construct(
        #[Assert\NotBlank]
        public string $title,
        #[Assert\NotBlank]
        public string $content,
        #[Assert\NotBlank]
        public string $summary,
        #[Assert\Uuid(versions: Assert\Uuid::V4_RANDOM)]
        public string $authorUuid,
    ) {
    }
}
