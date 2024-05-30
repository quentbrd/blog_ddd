<?php

namespace Blog\Application\Query;

use Blog\Domain\Query\QueryInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class FindAuthorByUuidQuery implements QueryInterface
{
    public function __construct(public UuidInterface $uuid)
    {
    }
}
