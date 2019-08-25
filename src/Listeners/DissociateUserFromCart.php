<?php
/**
 * Contains the DissociateUserFromCart listener class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-02-16
 *
 */

namespace Vanilo\Cart\Listeners;

use Vanilo\Cart\Facades\Cart;

class DissociateUserFromCart
{
    public function handle($event)
    {
        if (config('vanilo.cart.auto_assign_user') && !config('vanilo.cart.preserve_for_user')) {
            if (null !== Cart::getUser()) { // Prevent from surplus db operations
                Cart::removeUser();
            }
        }
    }
}
