<?php

use FastD\ServiceProvider\DatabaseServiceProvider;

return [
    /*
     * The application name.
     */
    'name'       => 'super_monitor',

    /*
     * The application timezone.
     */
    'timezone'   => 'PRC',

    /*
     * The Application namespace
     */
    'namespace'  => '\\App\\Controller\\',

    /*
     * Bootstrap service.
     */
    'services'   => [
        \FastD\ServiceProvider\ConfigServiceProvider::class,
        \FastD\ServiceProvider\RouteServiceProvider::class,
        \FastD\ServiceProvider\LoggerServiceProvider::class,
        DatabaseServiceProvider::class,
        \FastD\ServiceProvider\CacheServiceProvider::class,
        \FastD\Viewer\Viewer::class,
        \Zhaqq\Eloquent\EloquentServiceProvider::class,
        \App\Provider\AppProvider::class,
    ],

    /*
     * Http middleware
     */
    'middleware' => [
    ],

    /*
     * Application logger path
     */
    'log'        => [
        [
            \Monolog\Handler\StreamHandler::class,
            'error.log',
            \Monolog\Logger::ERROR,
        ],
    ],

    /*
     * Exception handle
     */
    'exception'  => [
        'response' => function (Exception $e) {
            return [
                'msg'   => $e->getMessage(),
                'code'  => $e->getCode(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ];
        },
        'log'      => function (Exception $e) {
            return [
                'msg'   => $e->getMessage(),
                'code'  => $e->getCode(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
                'trace' => explode("\n", $e->getTraceAsString()),
            ];
        },
    ],
];
