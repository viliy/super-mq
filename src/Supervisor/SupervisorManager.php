<?php

declare(strict_types=1);

/**
 * Class SupervisorManager.
 */

namespace App\Supervisor;

use App\Model\Process;
use App\RabbitMQ\MqClient;
use Illuminate\Support\Collection;
use Zhaqq\XmlRpc\Monitor\ProcessManager;

/**
 * Class SupervisorManager.
 */
class SupervisorManager extends ProcessManager
{
    /**
     * @var Collection
     */
    protected $mq;

    protected $mqKey;

    protected $supervisorInfo = [];

    public function __construct(array $config = [], $mqKey = 'default')
    {
        $this->getProcessesName();
        $this->mqKey = $mqKey;
        parent::__construct($config);
    }

    public function watch()
    {
        $this->setMq(); // 获取当前mq信息
        $this->setSupervisorInfo();
        if ($this->getProcesses()) {
            foreach ($this->getProcesses() as $key => $process) {
                if (!$this->hasSupervisorInfoGroup($key)) {
                    continue;
                }
                if (!$this->getMq()->has($process['mq_name'])) {
                    continue;
                }
                $queue = $this->getMq()->get($process['mq_name']);
                if ($queue['consumers'] < $process['mq_min_consumer']) {
                    try {
                        $this->rpcClient->stopProcessGroup($key, true);
                    } catch (\Throwable $exception) {
                        var_dump($exception->getMessage());
                    }
                    $this->rpcClient->startProcessGroup($key);
                }
            }
        }

        return true;
    }

    /**
     * @param string $group
     *
     * @return array|bool|string
     */
    public function restart(string $group)
    {
        try {
            $this->rpcClient->stopProcessGroup($group, true);
        } catch (\Throwable $exception) {
        }

        return $this->rpcClient->startProcessGroup($group);
    }

    /**
     * @param string $group
     *
     * @return array|bool|string
     */
    public function stop(string $group)
    {
        return $this->rpcClient->stopProcessGroup($group, true);
    }

    public function getProcessesName()
    {
        $this->setProcesses(Process::statusMonitor()->get()->keyBy('group')->toArray());
    }

    public function setMq()
    {
        /* @var $mqClient MqClient */
        $mqClient = app()->get('mq_client_' . $this->mqKey);
        $this->mq = collect($mqClient->queues())->keyBy('name');
    }

    /**
     * @return Collection
     */
    public function getMq()
    {
        return $this->mq;
    }

    public function getMqQueue(string $name)
    {
        return $this->mq->has($name);
    }

    /**
     * @return array
     */
    public function getSupervisorInfo(): array
    {
        return $this->supervisorInfo;
    }

    /**
     * @param string $group
     *
     * @return bool
     */
    public function hasSupervisorInfoGroup(string $group): bool
    {
        return isset($this->supervisorInfo[$group]);
    }

    public function setSupervisorInfo()
    {
        $supervisorInfo       = $this->rpcClient->getAllProcessInfo();
        $this->supervisorInfo = collect($supervisorInfo)->map(function ($item) {
            return [
                'name'      => $item['name'],
                'group'     => $item['group'],
                'statename' => $item['statename'],
                'runtime'   => (int)(($item['now'] - $item['start']) / 60),
                'start'     => date('Y-m-d H:i:s', $item['start']),
                'now'       => date('Y-m-d H:i:s', $item['now']),
                'stop'      => date('Y-m-d H:i:s', $item['stop']),
            ];
        })->groupBy('group')->toArray();
    }

    public function getSupervisorList()
    {
        $supervisorInfo = $this->rpcClient->getAllProcessInfo();

        return collect($supervisorInfo)->map(function ($item) {
            return [
                'name'      => $item['name'],
                'group'     => $item['group'],
                'statename' => $item['statename'],
                'runtime'   => (int)(($item['now'] - $item['start']) / 60),
                'start'     => date('Y-m-d H:i:s', $item['start']),
                'now'       => date('Y-m-d H:i:s', $item['now']),
                'stop'      => date('Y-m-d H:i:s', $item['stop']),
            ];
        })->toArray();
    }
}
