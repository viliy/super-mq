<?php

declare(strict_types=1);

/**
 * Class Process.
 */

namespace App\Model;

use App\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @method static Builder statusMonitor()
 * @method static Process system($system)
 * @Class Process.
 */
class Process extends Base
{
    use SoftDeletes;

    const STATUS_CLOSED  = 0;
    const STATUS_MONITOR = 1;

    protected $fillable = [
        'id',
        'name',
        'group',
        'system_id',
        'mq_name',
        'mq_node',
        'mq_vhost',
        'mq_consumer',
        'mq_min_consumer',
        'abstract',
        'status',
    ];

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeStatusMonitor(Builder $query)
    {
        return $query->where([
            'status' => self::STATUS_MONITOR,
        ]);
    }

    /**
     * @param Builder $query
     * @param         $system
     *
     * @return Builder
     *
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function scopeSystem(Builder $query, $system)
    {
        return $query->where([
            'system_id' => Cache::systemId()[$system] ?? 1,
        ]);
    }
}
