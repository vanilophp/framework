<?php

declare(strict_types=1);

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

/**
 * @method static OrderStatus PENDING();
 * @method static OrderStatus PROCESSING();
 * @method static OrderStatus COMPLETED();
 * @method static OrderStatus CANCELLED();
 *
 * @method bool isPending()
 * @method bool isProcessing()
 * @method bool isCompleted()
 * @method bool isCancelled()
 *
 * @property-read bool $is_pending
 * @property-read bool $is_processing
 * @property-read bool $is_completed
 * @property-read bool $is_cancelled
 */
class OrderStatus extends Enum implements OrderStatusContract
{
    public const __DEFAULT = self::PENDING;

    /**
     * Pending orders are brand new orders that have not been processed yet.
     */
    public const PENDING = 'pending';

    /** Order is being packed, manufactured, or partially fulfilled */
    public const PROCESSING = 'processing';

    /**
     * Orders fulfilled completely.
     */
    public const COMPLETED = 'completed';

    /**
     * Order that has been cancelled.
     */
    public const CANCELLED = 'cancelled';

    // $labels static property needs to be defined
    public static $labels = [];

    protected static $openStatuses = [self::PENDING, self::PROCESSING];

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
            self::PENDING => __('Pending'),
            self::PROCESSING => __('Processing'),
            self::COMPLETED => __('Completed'),
            self::CANCELLED => __('Cancelled')
        ];
    }
}
