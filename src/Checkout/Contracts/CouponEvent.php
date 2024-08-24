<?php

declare(strict_types=1);

namespace Vanilo\Checkout\Contracts;

interface CouponEvent extends CheckoutEvent
{
    public function getCouponCode(): string;
}
