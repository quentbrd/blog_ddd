<?php

namespace Blog\Application\Query;

use Blog\Domain\Query\QueryInterface;
use Ramsey\Uuid\UuidInterface;

final readonly class FindPostByUuidQuery implements QueryInterface
{
    public function __construct(public UuidInterface $uuid)
    {
    }
}
