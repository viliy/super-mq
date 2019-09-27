<?php

namespace App\Provider;

use App\Cache;
use App\Model\System;
use App\RabbitMQ\MqClient;
use FastD\Container\Container;
use App\Supervisor\SupervisorManager;
use FastD\Container\ServiceProviderInterface;

class AppProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     *
     * @return mixed|void
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function register(Container $container)
    {
        try {
            $config = Cache::system();
            foreach ($config as $key => $value) {
                $mq    = [
                'base_uri' => $value['mq_api_dns'],
                'auth'     => json_decode($value['mq_auth'], true),
            ];
                $auth  = json_decode($value['super_auth'], true) ?: [];
                $super = [
                'dns'      => $value['super_dns'],
                'username' => $auth['username'] ?? '',
                'password' => $auth['password'] ?? '',
            ];
                $container->add('mq_client_' . $key, new MqClient($mq));
                $container->add('super_manager_' . $key, new SupervisorManager($super, $key));
            }
        } catch (\Throwable $exception) {
            var_dump($exception->getMessage());
        }
    }
}
