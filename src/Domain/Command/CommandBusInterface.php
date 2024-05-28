<?php

declare(strict_types=1);

namespace Blog\Domain\Command;

interface CommandBusInterface
{
    public function dispatch(RoutableCommandInterface $command): mixed;
}
