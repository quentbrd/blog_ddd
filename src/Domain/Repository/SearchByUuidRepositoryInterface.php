<?php

namespace Blog\Domain\Repository;

use Ramsey\Uuid\UuidInterface;

/**
 * @template T of object
 *
 * @extends RepositoryInterface<T>
 */
interface SearchByUuidRepositoryInterface extends RepositoryInterface
{
    /**
     * @return T of object|null
     */
    public function searchByUuid(UuidInterface $uuid): ?object;
}
