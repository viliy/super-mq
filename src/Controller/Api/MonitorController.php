<?php

declare(strict_types=1);

/**
 * Class SupervisorController.
 */

namespace App\Controller\Api;

use App\Model\System;
use App\Model\Process;
use FastD\Http\ServerRequest;

class MonitorController
{
    /**
     * @param ServerRequest $request
     *
     * @return \FastD\Http\Response
     */
    public function index(ServerRequest $request)
    {
        $host = $request->getParam('system', 1);
        $data = Process::system($host)->paginate($request->getParam('per_page', 10))->toArray();
        if ($data) {
            $data['total']    = (int)$data['total'];
            $data['per_page'] = (int)$data['per_page'];
        }

        return json($data);
    }

    /**
     * @param ServerRequest $request
     *
     * @return \FastD\Http\Response
     */
    public function update(ServerRequest $request)
    {
        $id = $request->getAttribute('id', false);
        if (!$id) {
            return json(['message' => '请选择ID'], 422);
        }
        $monitor = Process::find($id);
        if (!$monitor) {
            return json([
                'message' => '资源不存在',
            ], 404);
        }
        $result = $monitor->update($request->bodyParams);

        return json([
            'message' => $result ? '更新成功' : '更新失败',
        ]);
    }

    /**
     * @param ServerRequest $request
     *
     * @return \FastD\Http\Response
     */
    public function status(ServerRequest $request)
    {
        $id      = $request->getAttribute('id', false);
        $process = Process::find($id)->first();
        if (!$process) {
            return json([
                'message' => '资源不存在',
            ], 404);
        }
        if (!$process->group && !$process->name) {
            return json([
                'message' => '请先完善基础信息',
            ], 422);
        }
        $process->status = (int)$request->getParam('status', 0);

        return json([
            'message' => $process->save() ? '更新成功' : '更新失败',
        ]);
    }

    public function system()
    {
        return json(System::statusMonitor()->select(['id', 'name'])->get()->map(function ($item) {
            $item['id'] = (string)$item['id'];

            return $item;
        })->toArray() ?: []);
    }
}
