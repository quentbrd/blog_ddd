<?php

declare(strict_types=1);

namespace Blog\Domain\Command;

abstract class AbstractPersistCommand implements CommandInterface
{
    /**
     * @param array<string, mixed> $primitives
     */
    final protected function __construct(public readonly array $primitives)
    {
    }
}
