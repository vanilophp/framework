<?php
/**
 * Contains the OrderStatus enum class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-27
 *
 */

namespace Vanilo\Order\Models;

use Konekt\Enum\Enum;
use Vanilo\Order\Contracts\OrderStatus as OrderStatusContract;

class OrderStatus extends Enum implements OrderStatusContract
{
    const __DEFAULT = self::PENDING;

    /**
     * Pending orders are brand new orders that have not been processed yet.
     */
    const PENDING = 'pending';

    /**
     * Orders fulfilled completely.
     */
    const COMPLETED = 'completed';

    /**
     * Order that has been cancelled.
     */
    const CANCELLED = 'cancelled';

    // $labels static property needs to be defined
    public static $labels = [];

    protected static $openStatuses = [self::PENDING];

    public function isOpen(): bool
    {
        return in_array($this->value, static::$openStatuses);
    }

    public static function getOpenStatuses(): array
    {
        return static::$openStatuses;
    }

    protected static function boot()
    {
        static::$labels = [
            self::PENDING     => __('Pending'),
            self::COMPLETED   => __('Completed'),
            self::CANCELLED   => __('Cancelled')
        ];
    }
}
