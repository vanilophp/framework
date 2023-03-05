<?php

declare(strict_types=1);

/**
 * Contains the CheckoutEvent interface.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Checkout\Contracts;

interface CheckoutEvent
{
    public function getCheckout(): Checkout;
}
