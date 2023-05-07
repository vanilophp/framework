<?php

declare(strict_types=1);

/**
 * Contains the PaymentStatus class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-28
 */

namespace Vanilo\Payment\Models;

use Konekt\Enum\Enum;
use Vanilo\Payment\Contracts\PaymentStatus as PaymentStatusContract;

/**
 * @method static PaymentStatus PENDING()
 * @method static PaymentStatus AUTHORIZED()
 * @method static PaymentStatus ON_HOLD()
 * @method static PaymentStatus PAID()
 * @method static PaymentStatus PARTIALLY_PAID()
 * @method static PaymentStatus DECLINED()
 * @method static PaymentStatus TIMEOUT()
 * @method static PaymentStatus CANCELLED()
 * @method static PaymentStatus REFUNDED()
 * @method static PaymentStatus PARTIALLY_REFUNDED()
 *
 * @method bool isPending()
 * @method bool isAuthorized()
 * @method bool isOnHold()
 * @method bool isPaid()
 * @method bool isPartiallyPaid()
 * @method bool isDeclined()
 * @method bool isTimeout()
 * @method bool isCancelled()
 * @method bool isRefunded()
 * @method bool isPartiallyRefunded()
 *
 * @property-read bool $is_pending
 * @property-read bool $is_authorized
 * @property-read bool $is_on_hold
 * @property-read bool $is_paid
 * @property-read bool $is_partially_paid
 * @property-read bool $is_declined
 * @property-read bool $is_timeout
 * @property-read bool $is_cancelled
 * @property-read bool $is_refunded
 * @property-read bool $is_partially_refunded
 */
class PaymentStatus extends Enum implements PaymentStatusContract
{
    public const __DEFAULT = self::PENDING;
    public const PENDING = 'pending';
    public const AUTHORIZED = 'authorized';
    public const ON_HOLD = 'on_hold';
    public const PAID = 'paid'; // aka. captured
    public const PARTIALLY_PAID = 'partially_paid';
    public const DECLINED = 'declined';
    public const TIMEOUT = 'timeout';
    public const CANCELLED = 'cancelled';
    public const REFUNDED = 'refunded';
    public const PARTIALLY_REFUNDED = 'partially_refunded';

    protected static $labels = [];

    protected static function boot()
    {
        static::$labels = [
            self::PENDING => __('Pending'),
            self::AUTHORIZED => __('Authorized'),
            self::ON_HOLD => __('On hold'),
            self::PAID => __('Paid'),
            self::PARTIALLY_PAID => __('Partially Paid'),
            self::DECLINED => __('Declined'),
            self::CANCELLED => __('Cancelled'),
            self::REFUNDED => __('Refunded'),
            self::PARTIALLY_REFUNDED => __('Partially Refunded'),
            self::TIMEOUT => __('Timed out'),
        ];
    }
}
