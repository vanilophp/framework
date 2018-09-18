<?php
/**
 * Contains the RestoreLastCart class.
 *
 * @copyright   Copyright (c) 2018 Conrad Hellmann
 * @author      Conrad Hellmann
 * @license     MIT
 * @since       2018-08-17
 *
 */


namespace Vanilo\Cart\Listeners;

use Vanilo\Cart\Facades\Cart;

class RestoreLastCart
{
    /**
     * Assign a user to the cart
     *
     * @param $event
     */
    public function handle($event)
    {
        if (Cart::isEmpty()) { //dont overwrite a not-empty cart
            Cart::restoreLastCart();
        }
    }
}
