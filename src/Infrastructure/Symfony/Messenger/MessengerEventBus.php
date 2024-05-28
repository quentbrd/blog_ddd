<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Symfony\Messenger;

use Blog\Domain\Event\EventBusInterface;
use Blog\Domain\Event\EventInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\ValidationStamp;

final class MessengerEventBus implements EventBusInterface
{
    public function __construct(
        private MessageBusInterface $eventBus
    ) {
    }

    public function publish(EventInterface ...$events): void
    {
        foreach ($events as $event) {
            try {
                $this->eventBus->dispatch((new Envelope($event))->with(new ValidationStamp([])));
            } catch (HandlerFailedException $e) {
                /**
                 * @var array{0: \Throwable} $exceptions
                 */
                $exceptions = $e->getWrappedExceptions();

                throw current($exceptions);
            }
        }
    }
}
