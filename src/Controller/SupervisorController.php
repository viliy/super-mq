<?php

declare(strict_types=1);

/**
 * Class SupervisorController.
 */

namespace App\Controller;

use App\Cache;

/**
 * Class SupervisorController.
 */
class SupervisorController
{
    /**
     * @return \FastD\Http\Response
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function index()
    {
        return render('supervisor.twig', [
            'system' => json_encode(Cache::systemIdAndName(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]);
    }
}
