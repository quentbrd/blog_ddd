<?php

declare(strict_types=1);

namespace Blog\Infrastructure\ApiPlatform\View;

use Blog\Domain\Model\Author;

final class AuthorViewFactory
{
    public function fromModel(Author $author): AuthorView
    {
        return new AuthorView($author->getName());
    }
}
