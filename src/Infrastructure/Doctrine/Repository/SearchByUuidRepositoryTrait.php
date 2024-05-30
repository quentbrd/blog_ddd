<?php

namespace Blog\Infrastructure\Doctrine\Repository;

use Ramsey\Uuid\UuidInterface;

trait SearchByUuidRepositoryTrait
{
    public function searchByUuid(UuidInterface $uuid): ?object
    {
        return $this->findOneBy([
            'uuid' => $uuid,
        ]);
    }
}
