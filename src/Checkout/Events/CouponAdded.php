<?php

declare(strict_types=1);

/**
 * Contains the CouponAdded class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-07-30
 *
 */

namespace Vanilo\Checkout\Events;

use Vanilo\Checkout\Contracts\Checkout;

class CouponAdded extends BaseCheckoutEvent
{
    public readonly string $couponCode;

    public function __construct(Checkout $checkout, string $couponCode)
    {
        parent::__construct($checkout);
        $this->couponCode = $couponCode;
    }
}
