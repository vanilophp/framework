<?php

declare(strict_types=1);

/**
 * Contains the BaseCartEvent class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-07
 *
 */

namespace Vanilo\Cart\Events;

use Vanilo\Cart\Contracts\Cart;
use Vanilo\Cart\Contracts\CartEvent;

abstract class BaseCartEvent implements CartEvent
{
    public function __construct(protected Cart $cart)
    {
    }

    public function getCart(): Cart
    {
        return $this->cart;
    }
}
