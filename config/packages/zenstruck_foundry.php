<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    if ('dev' === $containerConfigurator->env()) {
        $containerConfigurator->extension('zenstruck_foundry', [
            'auto_refresh_proxies' => true,
        ]);
    }
    if ('test' === $containerConfigurator->env()) {
        $containerConfigurator->extension('zenstruck_foundry', [
            'auto_refresh_proxies' => true,
        ]);
    }
};
