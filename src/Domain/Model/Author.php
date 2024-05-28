<?php

namespace Blog\Domain\Model;

use Ramsey\Uuid\UuidInterface;

class Author
{
    public function __construct(
        private int $id,
        private UuidInterface $uuid,
        private string $name,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUuid(): UuidInterface
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
