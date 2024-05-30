<?php

namespace Blog\Domain\Repository;

use Blog\Domain\Model\BlogPost;

/**
 * @extends RepositoryInterface<BlogPost>
 * @extends SearchByUuidRepositoryInterface<BlogPost>
 */
interface PostRepositoryInterface extends RepositoryInterface, SearchByUuidRepositoryInterface
{
}
