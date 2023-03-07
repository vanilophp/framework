<?php

declare(strict_types=1);

/**
 * Contains the DeleteCartAdjustments class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-07
 *
 */

namespace Vanilo\Foundation\Listeners;

use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Cart\Contracts\CartEvent;

class DeleteCartAdjustments
{
    public function handle(CartEvent $event): void
    {
        $cart = $event->getCart();
        if (!($cart instanceof Adjustable)) {
            return;
        }

        $cart->invalidateAdjustments();
        $cart->adjustments()->clear();// Method is available since v3.6; to be added to the interface in v4
    }
}
