<?php

declare(strict_types=1);

namespace Blog\Infrastructure\ApiPlatform\Resource;

use Blog\Domain\Model\BlogPost;
use Blog\Infrastructure\ApiPlatform\View\AuthorViewFactory;

final class BlogPostResourceFactory
{
    public function __construct(private AuthorViewFactory $authorViewFactory)
    {
    }

    public function fromModel(BlogPost $post): BlogPostResource
    {
        return new BlogPostResource(
            $post->getUuid(),
            $post->getTitle(),
            $post->getContent(),
            $post->getSummary(),
            $this->authorViewFactory->fromModel($post->getAuthor()),
            $post->getCreatedAt(),
            $post->getUpdatedAt(),
        );
    }
}
