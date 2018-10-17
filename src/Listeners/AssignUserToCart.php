<?php
/**
 * Contains the AssignUserToCart class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-02-14
 *
 */

namespace Vanilo\Cart\Listeners;

use Vanilo\Cart\Facades\Cart;

class AssignUserToCart
{
    /**
     * Assign a user to the cart
     *
     * @param $event
     */
    public function handle($event)
    {
        if (config('vanilo.cart.auto_assign_user')) {
            if (Cart::getUser() && Cart::getUser()->id == $event->user->id) {
                return; // Don't associate to the same user again
            }
            Cart::setUser($event->user);
        }
    }
}
