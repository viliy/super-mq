<?php

declare(strict_types=1);

/**
 * Class Cache.
 */

namespace App;

use App\Model\System;

/**
 * Class Cache.
 */
class Cache
{
    /**
     * @return bool|mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function system()
    {
        return static::cache_middleware(
            app()->getName() . '_system',
            function () {
                return System::statusMonitor()->get()->keyBy('name')->toArray();
            },
            3600
        );
    }

    /**
     * @return bool|mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function systemId()
    {
        return static::cache_middleware(
            app()->getName() . '_system_id',
            function () {
                return System::statusMonitor()->get()->keyBy('id')->toArray();
            },
            3600
        );
    }

    /**
     * @return bool|mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function systemIdAndName()
    {
        return static::cache_middleware(
            app()->getName() . '_system_name',
            function () {
                return System::statusMonitor()->select(['id', 'name'])->get()->map(function ($system) {
                    $system->id = (string)$system->id;

                    return $system;
                })->toArray();
            },
            3600
        );
    }

    /**
     * @param      $key
     * @param      $callback
     * @param null $expire
     *
     * @return bool|mixed
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public static function cache_middleware($key, $callback, $expire = null)
    {
        $item = cache()->getItem($key);
        if (true) {
            if (false === $data = call_user_func($callback)) {
                return false;
            }
            $item->set($data);
            if (!is_null($expire)) {
                $item->expiresAfter($expire);
            }
            cache()->save($item);
        }

        return $item->get();
    }
}
