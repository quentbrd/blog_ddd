<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('doctrine', [
        'dbal' => [
            'url' => '%env(resolve:DATABASE_URL)%',
            'profiling_collect_backtrace' => '%kernel.debug%',
            'use_savepoints' => true,
        ],
        'orm' => [
            'auto_generate_proxy_classes' => true,
            'enable_lazy_ghost_objects' => true,
            'report_fields_where_declared' => true,
            'validate_xml_mapping' => true,
            'naming_strategy' => 'doctrine.orm.naming_strategy.underscore_number_aware',
            'auto_mapping' => true,
            'mappings' => [
                'Blog/Domain/Model' => [
                    'type' => 'xml',
                    'is_bundle' => false,
                    'dir' => '%kernel.project_dir%/src/Infrastructure/Doctrine/Mapping',
                    'prefix' => 'Blog\Domain\Model',
                ],
            ],
            'controller_resolver' => [
                'auto_mapping' => true,
            ],
        ],
    ]);
};
