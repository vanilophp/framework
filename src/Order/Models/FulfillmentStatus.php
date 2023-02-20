<?php

declare(strict_types=1);

/**
 * Contains the FulfillmentStatus class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-20
 *
 */

namespace Vanilo\Order\Models;

use Konekt\Enum\Enum;
use Vanilo\Order\Contracts\FulfillmentStatus as FulfillmentStatusContract;

/**
 * @method static FulfillmentStatus SCHEDULED();
 * @method static FulfillmentStatus UNFULFILLED();
 * @method static FulfillmentStatus ON_HOLD();
 * @method static FulfillmentStatus AWAITING_SHIPMENT();
 * @method static FulfillmentStatus PARTIALLY_FULFILLED();
 * @method static FulfillmentStatus FULFILLED();
 *
 * @method bool isScheduled()
 * @method bool isUnfulfilled()
 * @method bool isOnHold()
 * @method bool isAwaitingShipment()
 * @method bool isPartiallyFulfilled()
 * @method bool isFulfilled()
 *
 * @property-read bool $is_scheduled
 * @property-read bool $is_unfulfilled
 * @property-read bool $is_on_hold
 * @property-read bool $is_awaiting_shipment
 * @property-read bool $is_partially_fulfilled
 * @property-read bool $is_fulfilled
 */
class FulfillmentStatus extends Enum implements FulfillmentStatusContract
{
    public const __DEFAULT = self::UNFULFILLED;

    /** Typically used by subscription orders that have a scheduled status until the fulfillment date is reached. */
    public const SCHEDULED = 'scheduled';

    /** A fresh order/item is unfulfilled by default. */
    public const UNFULFILLED = 'unfulfilled';

    /**  An item is on hold when there's a reservation but the transaction has not been confirmed/paid yet.
     *   Processing should not begin until the order/transaction is confirmed.
     */
    public const ON_HOLD = 'on_hold';

    /** The item has been picked, processed, and packaged, and is ready for delivery.
     *  It needs a pickup by the delivery personnel
     */
    public const AWAITING_SHIPMENT = 'awaiting';

    /** Only some parts of the subject have been handed over to a delivery personnel */
    public const PARTIALLY_FULFILLED = 'partially_fulfilled';

    /** The item has been handed over to a delivery personnel */
    public const FULFILLED = 'fulfilled';

    protected static $labels = [];

    protected static function boot()
    {
        static::$labels = [
            self::SCHEDULED => __('Scheduled'),
            self::ON_HOLD => __('On hold'),
            self::UNFULFILLED => __('Unfulfilled'),
            self::AWAITING_SHIPMENT => __('Awaiting shipment'),
            self::PARTIALLY_FULFILLED => __('Partially fulfilled'),
            self::FULFILLED => __('Fulfilled'),
        ];
    }
}
