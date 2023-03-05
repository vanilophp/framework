<?php

declare(strict_types=1);

/**
 * Contains the ShippingMethodSelected class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Checkout\Events;

use Vanilo\Checkout\Contracts\Checkout;

class ShippingMethodSelected extends BaseCheckoutEvent
{
    protected null|int|string $oldShippingMethodId;
    protected null|int|string $newShippingMethodId;

    public function __construct(Checkout $checkout, null|int|string $oldShippingMethodId)
    {
        parent::__construct($checkout);
        $this->oldShippingMethodId = $oldShippingMethodId;
        $this->newShippingMethodId = $checkout->getShippingMethodId();
    }

    public function selectedShippingMethodId(): null|int|string
    {
        return $this->newShippingMethodId;
    }

    public function oldShippingMethodId(): null|int|string
    {
        return $this->oldShippingMethodId;
    }
}
