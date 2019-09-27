<?php

namespace App\Controller\Api;

use App\Cache;
use App\Model\Process;
use App\RabbitMQ\Storage;
use FastD\Http\ServerRequest;
use App\Supervisor\SupervisorManager;

/**
 * Class MqController.
 */
class MqController
{
    /**
     * @param ServerRequest $request
     *
     * @return \FastD\Http\Response
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function index(ServerRequest $request)
    {
        $host = $request->getParam('system', 1);
        $data = app()->get('mq_client_' . Cache::systemId()[$host]['name'])->queues();

        return json($data);
    }

    /**
     * @param ServerRequest $request
     *
     * @return \FastD\Http\Response
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function update(ServerRequest $request)
    {
        $host   = $request->getParam('system', 1);
        $number = (new Storage())->update($host);

        return json([
            'message' => '更新成功',
            'number'  => $number,
        ]);
    }

    /**
     * @param ServerRequest $request
     *
     * @return \FastD\Http\Response
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function store(ServerRequest $request)
    {
        $host   = $request->getParam('system', 1);
        $number = (new Storage())->store($host);

        return json([
            'message' => '更新成功',
            'number'  => $number,
        ]);
    }

    public function info(ServerRequest $request)
    {
        $host = $request->getParam('system', 1);
        /* @var $super SupervisorManager */
        $super = app()->get('super_manager_' . $host);
        $super->setSupervisorInfo();
        $data = $super->getSupervisorInfo();

        return json([
            $data,
            Process::statusMonitor()->get()->groupBy('group')->toArray(),
        ]);
    }
}
