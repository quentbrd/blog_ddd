<?php

namespace Blog\Application\Query;

use Blog\Domain\Model\BlogPost;
use Blog\Domain\Query\QueryHandlerInterface;
use Blog\Domain\Repository\PostRepositoryInterface;

final readonly class FindPostByUuidQueryHandler implements QueryHandlerInterface
{
    public function __construct(private PostRepositoryInterface $postRepository)
    {
    }

    public function __invoke(FindPostByUuidQuery $query): ?BlogPost
    {
        return $this->postRepository->searchByUuid($query->uuid);
    }
}
