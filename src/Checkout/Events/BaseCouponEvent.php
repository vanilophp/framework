<?php

declare(strict_types=1);

/**
 * Contains the BaseCouponEvent class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-08-24
 *
 */

namespace Vanilo\Checkout\Events;

use Vanilo\Checkout\Contracts\Checkout;
use Vanilo\Checkout\Contracts\CouponEvent;

abstract class BaseCouponEvent extends BaseCheckoutEvent implements CouponEvent
{
    public readonly string $couponCode;

    public function __construct(Checkout $checkout, string $couponCode)
    {
        parent::__construct($checkout);
        $this->couponCode = $couponCode;
    }

    public function getCouponCode(): string
    {
        return $this->couponCode;
    }
}
