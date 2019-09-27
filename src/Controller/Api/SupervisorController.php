<?php

declare(strict_types=1);

/**
 * Class SupervisorController.
 */

namespace App\Controller\Api;

use App\Cache;
use FastD\Http\ServerRequest;
use App\Supervisor\SupervisorManager;
use Psr\Cache\InvalidArgumentException;

/**
 * Class SupervisorController.
 */
class SupervisorController
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
        /* @var $super SupervisorManager */
        $super = app()->get('super_manager_' . Cache::systemId()[$host]['name']);
        $super->setSupervisorInfo();
        $data = $super->getSupervisorList();

        return json($data);
    }

    /**
     * @param ServerRequest $request
     *
     * @return \FastD\Http\Response
     *
     * @throws InvalidArgumentException
     */
    public function restart(ServerRequest $request)
    {
        $host = $request->getParam('system', 1);
        try {
            /* @var $super SupervisorManager */
            $super = app()->get('super_manager_' . Cache::systemId()[$host]['name']);
            $super->restart($request->getParam('group'));
        } catch (\Throwable $e) {
            return json([
                'message' => $e->getMessage(),
            ], 500);
        }

        return json([
            'message' => '启动完成',
        ]);
    }

    /**
     * @param ServerRequest $request
     *
     * @return \FastD\Http\Response
     *
     * @throws InvalidArgumentException
     */
    public function stop(ServerRequest $request)
    {
        $host = $request->getParam('system', 1);
        try {
            /* @var $super SupervisorManager */
            $super = app()->get('super_manager_' . Cache::systemId()[$host]['name']);
            $super->stop($request->getParam('group'));
        } catch (\Throwable $e) {
            return json([
                'message' => $e->getMessage(),
            ], 500);
        }

        return json([
            'message' => '已停止运行',
        ]);
    }
}
