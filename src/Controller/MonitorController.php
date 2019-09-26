<?php

declare(strict_types=1);

/**
 * Class SupervisorController.
 */

namespace App\Controller;

use App\Model\System;

class MonitorController
{
    /**
     * @return \FastD\Http\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function index()
    {
        $systems = System::statusMonitor()->select(['id', 'name'])->get()->toArray();

        return render('monitor.twig', [
            'system' => json_encode(array_map(function ($system) {
                $system['id'] = (string)$system['id'];

                return $system;
            }, $systems), JSON_UNESCAPED_SLASHES),
        ]);
    }
}
