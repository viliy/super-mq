<?php

namespace App\Controller;

use App\Cache;

/**
 * Class MqController.
 */
class MqController
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
        return render('mq.twig', [
            'system' => json_encode(Cache::systemIdAndName(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
        ]);
    }
}
