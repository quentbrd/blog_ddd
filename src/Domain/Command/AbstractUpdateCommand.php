<?php

declare(strict_types=1);

namespace Blog\Domain\Command;

abstract class AbstractUpdateCommand extends AbstractPersistCommand
{
    public function id(): mixed
    {
        if (!array_key_exists('id', $this->primitives) && !array_key_exists('uuid', $this->primitives)) {
            throw new \RuntimeException(sprintf('Cannot retrieve identifier of "%s" because it\'s not in primitives', get_class($this)));
        }

        return $this->primitives['id'] ?? $this->primitives['uuid'];
    }
}
