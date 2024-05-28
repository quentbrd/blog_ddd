<?php

declare(strict_types=1);

namespace Blog\Infrastructure\Symfony\Messenger;

use Blog\Domain\Command\AsyncCommandInterface;
use Blog\Domain\Command\CommandBusInterface;
use Blog\Domain\Command\CommandInterface;
use Blog\Domain\Command\RoutableCommandInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\ValidationStamp;

final class MessengerCommandBus implements CommandBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    public function dispatch(RoutableCommandInterface $command): mixed
    {
        if ($command instanceof CommandInterface) {
            return $this->handleSync($command);
        }

        $this->handleRoutable($command);

        return null;
    }

    private function handleSync(CommandInterface $command): mixed
    {
        try {
            return $this->handle((new Envelope($command))->with(new ValidationStamp([])));
        } catch (HandlerFailedException $e) {
            /**
             * @var array{0: \Throwable} $exceptions
             */
            $exceptions = $e->getWrappedExceptions();

            throw current($exceptions);
        }
    }

    private function handleRoutable(RoutableCommandInterface $command): void
    {
        $envelope = (new Envelope($command))->with(new ValidationStamp([]));

        if ($command instanceof AsyncCommandInterface && 0 < $command->delay()) {
            $envelope = $envelope->with(new DelayStamp($command->delay()));
        }

        $this->messageBus->dispatch($envelope);
    }
}
