<?php

/**
 * Class MqClient.
 */

namespace App\RabbitMQ;

use GuzzleHttp\Client;

/**
 * Class MqClient.
 */
class MqClient
{
    const API    = 'api/';
    const QUEUES = 'queues';
    const APIS   = [
        'queues',
    ];

    /**
     * @var Client
     */
    protected $client;

    public function __construct(array $config = [])
    {
        if (isset($config['auth'])) {
            $config['auth'] = array_values($config['auth']);
        }
        $this->setClient(new \GuzzleHttp\Client($config));
    }

    /**
     * @return Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * @param mixed $client
     */
    public function setClient($client)
    {
        $this->client = $client;
    }

    public function __call($uri, $arguments)
    {
        if (in_array($uri, self::APIS)) {
            return  $this->getClient()->get(self::API . $uri)->getBody()->getContents();
        }

        return [];
    }

    /**
     * @return array
     */
    public function queues()
    {
        $response = $this->request(self::QUEUES);
        foreach ($response as $key => $value) {
            $info[$key]['consumers'] = $value['consumers'];
            $info[$key]['name']      = $value['name'];
            $info[$key]['vhost']     = $value['vhost'];
            $info[$key]['node']      = $value['node'];
        }

        return $info ?? [];
    }

    /**
     * @param $uri
     *
     * @return array
     */
    public function request($uri)
    {
        return \GuzzleHttp\json_decode($this->getClient()->get(self::API . $uri)->getBody()->getContents(), true);
    }
}
