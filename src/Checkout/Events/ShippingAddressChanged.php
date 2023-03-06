<?php

declare(strict_types=1);

/**
 * Contains the ShippingAddressChanged class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-06
 *
 */

namespace Vanilo\Checkout\Events;

use Vanilo\Contracts\Address;

class ShippingAddressChanged extends BaseCheckoutEvent
{
    public function newShippingAddress(): Address
    {
        return $this->checkout->getShippingAddress();
    }
}
