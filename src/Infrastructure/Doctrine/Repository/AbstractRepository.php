<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Doctrine\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @template T of object
 *
 * @extends ServiceEntityRepository<T>
 */
abstract class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, static::entityClass());
    }

    /**
     * @phpstan-return class-string<T>
     */
    abstract protected static function entityClass(): string;

    /**
     * @phpstan-param T $entity
     *
     * @phpstan-return T
     */
    public function save(object $entity): object
    {
        $this->getEntityManager()->persist($entity);
        $this->getEntityManager()->flush();

        return $entity;
    }

    /**
     * @phpstan-return T|null
     */
    public function search(int $id): ?object
    {
        return $this->find($id);
    }

    /**
     * @phpstan-return list<T>
     */
    public function all(): array
    {
        return $this->findAll();
    }

    public function exists(int $id): bool
    {
        return 0 < $this->count(['id' => $id]);
    }

    /**
     * @phpstan-param T $entity
     */
    public function delete(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}
