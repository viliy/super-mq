<?php

use App\Process\SupervisorProcess;

return [
    'host'      => '127.0.0.1:9998',
    'class'     => \FastD\Servitization\Server\HTTPServer::class,
    'options'   => [
        'pid_file'        => __DIR__ . '/../runtime/pid/' . app()->getName() . '.pid',
        'log_file'        => __DIR__ . '/../runtime/logs/' . app()->getName() . '.pid',
        'log_level'       => 5,
        'worker_num'      => 1,
        'task_worker_num' => 0,
    ],
    'processes' => [
        SupervisorProcess::class,
    ],
    'listeners' => [],
];
