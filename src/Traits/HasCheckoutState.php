<?php
/**
 * Contains the HasCheckoutState trait.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-23
 *
 */


namespace Vanilo\Checkout\Traits;

use Vanilo\Checkout\Contracts\CheckoutState;
use Vanilo\Checkout\Models\CheckoutStateProxy;

trait HasCheckoutState
{
    public function getState(): CheckoutState
    {
        return $this->state instanceof CheckoutState ? $this->state : CheckoutStateProxy::create($this->state);
    }

    public function setState($state)
    {
        $this->state = $state instanceof CheckoutState ? $state : CheckoutStateProxy::create($state);
    }
}
