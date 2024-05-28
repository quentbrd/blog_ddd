<?php

namespace Blog\Application\Query;

use Blog\Domain\Model\BlogPost;
use Blog\Domain\Query\QueryHandlerInterface;
use Blog\Domain\Repository\PostRepositoryInterface;

final readonly class FindPostsQueryHandler implements QueryHandlerInterface
{
    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }

    /**
     * @return iterable<BlogPost>
     */
    public function __invoke(FindPostsQuery $query): iterable
    {
        return $this->postRepository->all();
    }
}
