<?php

declare(strict_types=1);

namespace Blog\Domain\Event;

interface EventBusInterface
{
    public function publish(EventInterface ...$events): void;
}
