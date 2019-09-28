<?php
/**
 * Contains the Checkout facade class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-23
 *
 */

namespace Vanilo\Checkout\Facades;

use Illuminate\Support\Facades\Facade;
use Vanilo\Checkout\Contracts\CheckoutState;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Contracts\CheckoutSubject;

/**
 * @method static getCart(): ?CheckoutSubject
 * @method static setCart(CheckoutSubject $cart)
 * @method static getState(): CheckoutState
 * @method static setState(CheckoutState|string $state)
 * @method static getBillpayer(): Billpayer
 * @method static setBillpayer(Billpayer $billpayer)
 * @method static getShippingAddress(): Address
 * @method static setShippingAddress(Address $address)
 * @method static setCustomAttribute(string $key, $value): void
 * @method static getCustomAttribute(string $key)
 * @method static putCustomAttributes(array $data): void
 * @method static getCustomAttributes(): array
 * @method static update(array $data);
 * @method static total(): float
 */
class Checkout extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'vanilo.checkout';
    }
}
