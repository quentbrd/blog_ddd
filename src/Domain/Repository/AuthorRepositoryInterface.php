<?php

namespace Blog\Domain\Repository;

use Blog\Domain\Model\Author;

/**
 * @extends RepositoryInterface<Author>
 * @extends SearchByUuidRepositoryInterface<Author>
 */
interface AuthorRepositoryInterface extends RepositoryInterface, SearchByUuidRepositoryInterface
{
}
