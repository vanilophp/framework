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

use ArrayAccess;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Contracts\CheckoutSubject;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Contracts\Shippable;

interface Checkout extends Shippable, ArrayAccess
{
    public function getCart(): ?CheckoutSubject;

    public function setCart(CheckoutSubject $cart);

    public function getState(): CheckoutState;

    /**
     * Sets the state of the checkout
     *
     * @param CheckoutState|string $state
     */
    public function setState($state);

    public function getBillpayer(): Billpayer;

    public function setBillpayer(Billpayer $billpayer);

    public function setShippingAddress(Address $address): void;

    public function removeShippingAddress(): void;

    public function getShipToBillingAddress(bool $default = true): bool;

    public function setShipToBillingAddress(bool $value): void;

    public function getShippingMethodId(): null|int|string;

    public function setShippingMethodId(null|int|string $shippingMethodId): void;

    public function getPaymentMethodId(): null|int|string;

    public function setPaymentMethodId(null|int|string $paymentMethodId): void;

    public function getNotes(): ?string;

    public function setNotes(?string $text): void;

    public function clear(): void;

    public function setCustomAttribute(string $key, $value): void;

    public function getCustomAttribute(string $key);

    public function putCustomAttributes(array $data): void;

    public function getCustomAttributes(): array;

    public function getShippingAmount(): DetailedAmount;

    public function setShippingAmount(float|DetailedAmount $amount): void;

    public function getTaxesAmount(): DetailedAmount;

    public function setTaxesAmount(float|DetailedAmount $amount): void;

    /** Add these methods in v5 to the interface (their implementations exist since v4.2):
     * public function addCoupon(string $couponCode): void;
     * public function removeCoupon(string $couponCode): void;
     * public function hasCoupon(string $couponCode): bool;
     * public function getCoupons(): array;
     * public function hasAnyCoupon(): bool;
     * public function getPromotionsAmount(): DetailedAmount;
     * public function setPromotionsAmount(float|DetailedAmount $amount): void;
     */

    public function itemsTotal(): float;

    /**
     * Update checkout data with an array of attributes
     *
     * @deprecated
     *
     * @param array $data
     */
    public function update(array $data);

    public function total();
}
