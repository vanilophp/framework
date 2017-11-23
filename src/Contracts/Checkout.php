<?php
/**
 * Contains the Checkout interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-13
 *
 */


namespace Vanilo\Checkout\Contracts;


use Vanilo\Contracts\CheckoutSubject;

interface Checkout
{
    /**
     * Returns the cart
     *
     * @return CheckoutSubject|null
     */
    public function getCart();

    /**
     * Set the cart for the checkout
     *
     * @param CheckoutSubject $cart
     */
    public function setCart(CheckoutSubject $cart);

    /**
     * Returns the state of the checkout
     *
     * @return CheckoutState
     */
    public function getState(): CheckoutState;

    /**
     * Sets the state of the checkout
     *
     * @param CheckoutState|string $state
     */
    public function setState($state);

    /**
     * Returns the grand total of the checkout
     *
     * @return float
     */
    public function total();

}