<?php

declare(strict_types=1);

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
use Vanilo\Contracts\DetailedAmount;

interface Checkout
{
    public function getCart(): ?CheckoutSubject;

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

    public function getShippingAddress(): ?Address;

    public function setShippingAddress(Address $address): void;

    public function removeShippingAddress(): void;

    public function setCustomAttribute(string $key, $value): void;

    public function getCustomAttribute(string $key);

    public function putCustomAttributes(array $data): void;

    public function getCustomAttributes(): array;

    public function getShippingAmount(): DetailedAmount;

    public function setShippingAmount(float|DetailedAmount $amount): void;

    public function getTaxesAmount(): DetailedAmount;

    public function setTaxesAmount(float|DetailedAmount $amount): void;

    /**
     * Update checkout data with an array of attributes
     *
     * @deprecated
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
