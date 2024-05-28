<?php

namespace Blog\Infrastructure\Doctrine\Repository;

use Blog\Domain\Model\BlogPost;
use Blog\Domain\Repository\PostRepositoryInterface;

/**
 * @extends AbstractRepository<BlogPost>
 */
class PostRepository extends AbstractRepository implements PostRepositoryInterface
{
    protected static function entityClass(): string
    {
        return BlogPost::class;
    }
}
