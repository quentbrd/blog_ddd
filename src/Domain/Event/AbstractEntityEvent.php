<?php

declare(strict_types=1);

namespace Blog\Domain\Event;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractEntityEvent implements EventInterface
{
    public readonly string $occurredOn;
    public readonly string $eventUuid;

    /**
     * @param array<string, mixed> $context
     */
    public function __construct(
        public readonly UuidInterface $entityUuid,
        public readonly array $context = [],
    ) {
        $this->eventUuid = Uuid::uuid4()->toString();
        $this->occurredOn = (new \DateTimeImmutable())->format(\DateTimeInterface::ATOM);
    }

    abstract public static function eventName(): string;
}
