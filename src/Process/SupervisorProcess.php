<?php

declare(strict_types=1);

namespace App\Process;

use App\Cache;
use swoole_process;
use FastD\Process\AbstractProcess;

/**
 * Class SupervisorProcess.
 */
class SupervisorProcess extends AbstractProcess
{
    public function handle(swoole_process $swoole_process)
    {
        timer_tick(1000 * 10, function ($id) {
            static $index = 0;
            ++$index;
            echo $index . PHP_EOL;
            foreach (Cache::system() as $key => $value) {
                app()->get('super_manager_' . $key)->watch();
            }
        });
    }
}
