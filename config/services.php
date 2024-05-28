<?php

declare(strict_types=1);

use Blog\Infrastructure\ApiPlatform\Provider\DataProviderInterface;
use Blog\Infrastructure\ApiPlatform\Provider\Provider;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\tagged_iterator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure()
        ->instanceof(DataProviderInterface::class)->tag('blog.data_provider');

    $services->load('Blog\\', __DIR__.'/../src/')
        ->exclude([
            __DIR__.'/../src/Domain/Model/',
            __DIR__.'/../src/Infrastructure/Shared/Kernel.php',
        ]);

    $services->get(Provider::class)->arg('$dataProviders', tagged_iterator('blog.data_provider'));
};
