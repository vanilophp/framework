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


use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
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
     * Returns the bill payer details
     *
     * @return Billpayer
     */
    public function getBillpayer(): Billpayer;

    /**
     * Sets the bill payer details
     *
     * @param Billpayer $billpayer
     */
    public function setBillpayer(Billpayer $billpayer);

    /**
     * Returns the shipping address
     *
     * @return Address
     */
    public function getShippingAddress(): Address;

    /**
     * Sets the shipping address
     *
     * @param Address $address
     */
    public function setShippingAddress(Address $address);

    /**
     * Update checkout data with an array of attributes
     *
     * @param array $data
     */
    public function update(array $data);

    /**
     * Returns the grand total of the checkout
     *
     * @return float
     */
    public function total();

}
