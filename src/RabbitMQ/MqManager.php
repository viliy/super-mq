<?php

/**
 * Class MqManager.
 */

namespace App\RabbitMQ;

use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class MqManager.
 */
class MqManager
{
    /**
     * @var AMQPStreamConnection[]
     */
    protected static $connections;

    /**
     * @var AMQPStreamConnection
     */
    protected $connection;

    public function __construct(array $config = [])
    {
        $this->setConnections($config);
    }

    /**
     * @param mixed $connections
     */
    public function setConnections(array $connections = [])
    {
        foreach ($connections as $key => $connection) {
            self::$connections[$key] = new AMQPStreamConnection(
                $connection['host'],
                $connection['port'],
                $connection['user'],
                $connection['pass']
            );
        }
        $this->connection = self::$connections['default'] ?? current(self::$connections);
    }

    /**
     * @param string $name
     *
     * @return AMQPStreamConnection
     */
    public function getConnection($name = 'default'): AMQPStreamConnection
    {
        $this->connection = self::$connections[$name];

        return $this->connection;
    }
}
