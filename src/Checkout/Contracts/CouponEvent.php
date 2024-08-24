<?php

namespace Vanilo\Checkout\Contracts;

interface CouponEvent extends CheckoutEvent
{
    public function getCouponCode(): string;
}
