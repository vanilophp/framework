<?php

declare(strict_types=1);

/**
 * Contains the HasCartAndCheckout class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-08-21
 *
 */

namespace Vanilo\Foundation\Listeners;

use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Cart\Contracts\Cart;
use Vanilo\Cart\Contracts\CartEvent;
use Vanilo\Cart\Contracts\CartManager;
use Vanilo\Checkout\Contracts\Checkout;
use Vanilo\Checkout\Contracts\CheckoutEvent;
use Vanilo\Checkout\Facades\Checkout as CheckoutFacade;

trait HasCartAndCheckout
{
    protected ?Checkout $checkout;

    protected ?Cart $cart;

    protected ?Cart $cartModel;

    protected function initialize(CheckoutEvent|CartEvent $event): void
    {
        if ($event instanceof CheckoutEvent) {
            $this->checkout = $event->getCheckout();
            $this->cart = $this->checkout->getCart();
        } else {
            $this->cart = $event->getCart();
            CheckoutFacade::setCart($this->cart);
            $this->checkout = CheckoutFacade::getFacadeRoot();
        }

        $this->cartModel = $this->cart instanceof CartManager ? $this->cart->model() : $this->cart;
    }

    protected function theCartModelIsNotAdjustable(): bool
    {
        return !$this->cartModel instanceof Adjustable;
    }
}
