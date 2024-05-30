<?php

declare(strict_types=1);

use Blog\Domain\Exception\EntityCreationException;
use Blog\Domain\Exception\EntityNotFoundException;
use Blog\Domain\Exception\RelatedEntityNotFoundException;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('api_platform', [
        'title' => 'Blog API',
        'version' => '1.0.0',
        'mapping' => [
            'paths' => [
                '%kernel.project_dir%/src/Infrastructure/ApiPlatform/Resource',
            ],
        ],
        'formats' => [
            'jsonld' => [
                'application/ld+json',
            ],
        ],
        'docs_formats' => [
            'jsonld' => [
                'application/ld+json',
            ],
            'jsonopenapi' => [
                'application/vnd.openapi+json',
            ],
            'html' => [
                'text/html',
            ],
        ],
        'defaults' => [
            'stateless' => true,
            'cache_headers' => [
                'vary' => [
                    'Content-Type',
                    'Authorization',
                    'Origin',
                ],
            ],
            'extra_properties' => [
                'standard_put' => true,
                'rfc_7807_compliant_errors' => true,
            ],
        ],
        'keep_legacy_inflector' => false,
        'use_symfony_listeners' => true,
        'exception_to_status' => [
            InvalidArgumentException::class => 400,
            EntityNotFoundException::class => 404,
            EntityCreationException::class => 422,
            RelatedEntityNotFoundException::class => 422,
        ],
    ]);
};
