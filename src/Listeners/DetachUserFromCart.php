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

class DetachUserFromCart
{
    public function handle($event)
    {
        Cart::removeUser();
    }
}
