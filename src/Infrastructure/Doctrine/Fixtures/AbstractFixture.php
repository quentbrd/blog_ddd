<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Doctrine\Fixtures;

use Doctrine\Bundle\FixturesBundle\Fixture as DoctrineFixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;

abstract class AbstractFixture extends DoctrineFixture
{
    public function __construct(private EntityManagerInterface $em)
    {
        $this->em->getClassMetadata(static::modelClass())->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
    }

    /**
     * @return class-string
     */
    abstract protected static function modelClass(): string;
}
