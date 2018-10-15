<?php
/**
 * Contains the RestoreCurrentUsersLastActiveCart class.
 *
 * @copyright   Copyright (c) 2018 Conrad Hellmann
 * @author      Conrad Hellmann
 * @license     MIT
 * @since       2018-08-17
 *
 */

namespace Vanilo\Cart\Listeners;

use Illuminate\Support\Facades\Auth;
use Vanilo\Cart\Facades\Cart;

class RestoreCurrentUsersLastActiveCart
{
    public function handle($event)
    {
        if (Cart::isEmpty() && config('vanilo.cart.preserve_for_user') && Auth::check()) {
            Cart::restoreLastActiveCart(Auth::user());
        }
    }
}
