<?php

declare(strict_types=1);

namespace Blog\Application\Command;

use Blog\Domain\Command\CommandInterface;
use Blog\Domain\Model\Author;

final readonly class CreateBlogPostCommand implements CommandInterface
{
    public function __construct(
        public string $title,
        public string $content,
        public string $summary,
        public Author $author,
    ) {
    }
}
