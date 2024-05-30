<?php

declare(strict_types=1);

namespace Blog\Domain\Exception;

final class EntityCreationException extends \RuntimeException
{
    /**
     * @param class-string $class
     */
    public function __construct(string $class, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf('%s cannot be created.', $class), $code, $previous);
    }
}
