<?php

namespace Blog\Application\Query;

use Blog\Domain\Model\Author;
use Blog\Domain\Query\QueryHandlerInterface;
use Blog\Domain\Repository\AuthorRepositoryInterface;

final readonly class FindAuthorByUuidQueryHandler implements QueryHandlerInterface
{
    public function __construct(private AuthorRepositoryInterface $authorRepository)
    {
    }

    public function __invoke(FindAuthorByUuidQuery $query): ?Author
    {
        return $this->authorRepository->searchByUuid($query->uuid);
    }
}
