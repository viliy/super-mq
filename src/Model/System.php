<?php

declare(strict_types=1);

/**
 * Class System.
 */

namespace App\Model;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class System.
 *
 * @method static Builder statusMonitor()
 */
class System extends Base
{
    const STATUS_CLOSED  = 0;
    const STATUS_MONITOR = 1;

    /**
     * @param Builder $query
     * @param int     $status
     *
     * @return Builder
     */
    public function scopeStatusMonitor(Builder $query, $status = self::STATUS_MONITOR)
    {
        return $query->where([
            'status' => $status,
        ]);
    }

    public function getIdAttribute($value)
    {
        return (string)($value);
    }
}
