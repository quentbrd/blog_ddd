<?php

declare(strict_types=1);

namespace Blog\Domain\Query;

interface QueryBusInterface
{
    public function ask(QueryInterface $query): mixed;
}
