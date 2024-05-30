<?php

namespace Blog\Infrastructure\Doctrine\Repository;

use Blog\Domain\Model\BlogPost;
use Blog\Domain\Repository\PostRepositoryInterface;

/**
 * @extends AbstractRepository<BlogPost>
 *
 * @uses SearchByUuidRepositoryTrait<BlogPost>
 */
class PostRepository extends AbstractRepository implements PostRepositoryInterface
{
    use SearchByUuidRepositoryTrait;

    protected static function entityClass(): string
    {
        return BlogPost::class;
    }
}
