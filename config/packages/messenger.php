<?php

declare(strict_types=1);

use Blog\Domain\Command\CommandInterface;
use Blog\Domain\Event\EventInterface;
use Blog\Domain\Query\QueryInterface;
use Symfony\Config\FrameworkConfig;

return static function (FrameworkConfig $config): void {
    $messenger = $config->messenger();

    $messenger->defaultBus('command.bus');

    $commandBus = $messenger->bus('command.bus');
    $commandBus->middleware()->id('validation');
    $commandBus->middleware()->id('doctrine_transaction');

    $queryBus = $messenger->bus('query.bus');
    $queryBus->middleware()->id('validation');

    $eventBus = $messenger->bus('event.bus');
    $eventBus->defaultMiddleware()
        ->enabled(true)
        ->allowNoHandlers(true)
        ->allowNoSenders(true)
    ;
    $eventBus->middleware()->id('validation');

    $messenger->transport('sync')->dsn('sync://');

    $messenger->transport('async')
        ->dsn('%env(MESSENGER_TRANSPORT_DSN)%')
        ->failureTransport('failed')
        ->options(['auto_setup' => true])
    ;

    $messenger->transport('failed')
        ->dsn('doctrine://default')
        ->options([
            'auto_setup' => true,
            'queue_name' => 'failed',
        ])
    ;

    $messenger->routing(QueryInterface::class)->senders(['sync']);
    $messenger->routing(CommandInterface::class)->senders(['sync']);
    $messenger->routing(EventInterface::class)->senders(['sync']);
};
