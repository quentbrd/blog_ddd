<?php

declare(strict_types=1);

namespace Blog\Domain\Event;

interface EventInterface
{
    public static function eventName(): string;
}
