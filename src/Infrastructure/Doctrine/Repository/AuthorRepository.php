<?php

namespace Blog\Infrastructure\Doctrine\Repository;

use Blog\Domain\Model\Author;
use Blog\Domain\Repository\AuthorRepositoryInterface;

/**
 * @extends AbstractRepository<Author>
 *
 * @uses SearchByUuidRepositoryTrait<Author>
 */
class AuthorRepository extends AbstractRepository implements AuthorRepositoryInterface
{
    use SearchByUuidRepositoryTrait;

    protected static function entityClass(): string
    {
        return Author::class;
    }
}
