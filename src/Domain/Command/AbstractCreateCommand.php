<?php

declare(strict_types=1);

namespace Blog\Domain\Command;

abstract class AbstractCreateCommand extends AbstractPersistCommand
{
    /**
     * @param array<string, mixed> $primitives
     *
     * @return static
     */
    abstract public static function create(array $primitives): self;
}
