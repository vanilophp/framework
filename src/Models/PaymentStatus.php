<?php

declare(strict_types=1);

/**
 * Contains the PaymentStatus class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-28
 *
 */

namespace Vanilo\Payment\Models;

use Konekt\Enum\Enum;
use Vanilo\Payment\Contracts\PaymentStatus as PaymentStatusContract;

/**
 * @method static PaymentStatus PENDING()
 * @method static PaymentStatus PAID()
 * @method static PaymentStatus PARTIALLY_PAID()
 * @method static PaymentStatus DECLINED()
 * @method static PaymentStatus TIMEOUT()
 */
class PaymentStatus extends Enum implements PaymentStatusContract
{
    public const __DEFAULT      = self::PENDING;
    public const PENDING        = 'pending';
    public const PAID           = 'paid';
    public const PARTIALLY_PAID = 'partially_paid';
    public const DECLINED       = 'declined';
    public const TIMEOUT        = 'timeout';

    protected static $labels = [];

    protected static function boot()
    {
        static::$labels = [
            self::PENDING        => __('Pending'),
            self::PAID           => __('Paid'),
            self::PARTIALLY_PAID => __('Partially Paid'),
            self::DECLINED       => __('Declined'),
            self::TIMEOUT        => __('Timed out'),
        ];
    }
}
