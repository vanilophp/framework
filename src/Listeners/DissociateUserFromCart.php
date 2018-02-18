<?php
/**
 * Contains the DetachUserFromCart class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
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
        if (config('vanilo.cart.auto_assign_user')) {
            if (!is_null(Cart::getUser())) { // Prevent from surplus db operations
                Cart::removeUser();
            }
        }
    }
}
