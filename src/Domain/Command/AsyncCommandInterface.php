<?php

declare(strict_types=1);

namespace Blog\Domain\Command;

interface AsyncCommandInterface extends RoutableCommandInterface
{
    public function delay(): int;
}
