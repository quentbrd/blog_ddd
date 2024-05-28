<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Symfony\Messenger;

use Blog\Domain\Query\QueryBusInterface;
use Blog\Domain\Query\QueryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ValidationStamp;

final class MessengerQueryBus implements QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    public function ask(QueryInterface $query): mixed
    {
        try {
            return $this->handle((new Envelope($query))->with(new ValidationStamp([])));
        } catch (HandlerFailedException $e) {
            /**
             * @var array{0: \Throwable} $exceptions
             */
            $exceptions = $e->getWrappedExceptions();

            throw current($exceptions);
        }
    }
}
