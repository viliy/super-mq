<?php

return [
    'default' => [
        'adapter' => \Symfony\Component\Cache\Adapter\RedisAdapter::class,
        'params'  => [
            'dsn'   => 'redis://127.0.0.1:6379/2',
            'class' => Predis\Client::class,
        ],
    ],
];
