<?php

declare(strict_types=1);

/**
 * Contains the BaseCheckoutEvent class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Checkout\Events;

use Vanilo\Checkout\Contracts\Checkout;
use Vanilo\Checkout\Contracts\CheckoutEvent;

abstract class BaseCheckoutEvent implements CheckoutEvent
{
    public function __construct(protected Checkout $checkout)
    {
    }

    public function getCheckout(): Checkout
    {
        return $this->checkout;
    }
}
