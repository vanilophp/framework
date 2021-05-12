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
 */
interface PaymentStatus
{
    /** @return string */
    public function value();

    /** @return string */
    public function label();
}
