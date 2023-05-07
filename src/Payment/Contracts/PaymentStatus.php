<?php

declare(strict_types=1);

/**
 * Contains the PaymentStatus interface.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-17
 *
 */

namespace Vanilo\Payment\Contracts;

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
 */
interface PaymentStatus
{
    /** @return string */
    public function value();

    /** @return string */
    public function label();
}
