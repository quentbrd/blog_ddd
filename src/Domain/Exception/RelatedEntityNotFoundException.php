<?php

declare(strict_types=1);

namespace Blog\Domain\Exception;

final class RelatedEntityNotFoundException extends \RuntimeException
{
    public function __construct(string $class, string $relatedClass, mixed $id, string $idProperty = 'id', int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct(sprintf("%s related to %s with %s '%s' not found.", $class, $relatedClass, $idProperty, $id), $code, $previous);
    }
}
