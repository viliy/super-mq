<?php

declare(strict_types=1);

namespace App\RabbitMQ;

use App\Cache;
use App\Model\Process;

/**
 * Class Storage.
 */
class Storage
{
    /**
     * 初始化mq配置.
     *
     * @param $mq
     *
     * @return int
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function store($mq)
    {
        $data      = app()->get('mq_client_'.Cache::systemId()[$mq]['name'])->queues();
        $processes = Process::system($mq)
            ->select(['id', 'mq_name'])->withTrashed()->get()->pluck('id', 'mq_name')->toArray();
        $mqInfo    = collect($data)->keyBy('name')->toArray();
        $i         = 0;
        foreach ($mqInfo as $key => $value) {
            if (!isset($processes[$key])) {
                ++$i;
                Process::create([
                    'name'            => '',
                    'group'           => '',
                    'system_id'       => Cache::system()[$mq]['id'],
                    'mq_name'         => $value['name'],
                    'mq_node'         => $value['node'],
                    'mq_vhost'        => $value['vhost'],
                    'mq_consumer'     => $value['consumers'] ?: 1,
                    'mq_min_consumer' => $value['consumers'] ?: 1,
                    'abstract'        => date('Y-m-d H:i:s') . '新增,待配置',
                    'status'          => Process::STATUS_CLOSED,
                ]);
            }
        }

        return $i;
    }

    /**
     * 更新mq配置.
     *
     * @param $mq
     *
     * @return int
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function update($mq)
    {
        $data      = app()->get('mq_client_'.Cache::systemId()[$mq]['name'])->queues();
        $processes = Process::system($mq)
            ->select(['id', 'mq_name'])->withTrashed()->get()->keyBy('mq_name')->toArray();
        $mqInfo    = collect($data)->keyBy('name')->toArray();
        $i         = 0;
        foreach ($mqInfo as $key => $value) {
            if (isset($processes[$key])) {
                $process = Process::find($processes[$key])->first();
                if ($process) {
                    $process->system_id       = Cache::system()[$mq]['id'];
                    $process->mq_name         = $value['name'];
                    $process->mq_node         = $value['node'];
                    $process->mq_vhost        = $value['vhost'];
                    $process->mq_consumer     = $process->mq_consumer ?: 1;
                    $process->mq_min_consumer = $process->mq_min_consumer ?: 1;
                    $process->save() && ++$i;
                }
            }
        }

        return $i;
    }
}
