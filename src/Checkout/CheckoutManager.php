<?php

declare(strict_types=1);

/**
 * Contains the Checkout Manager class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-13
 *
 */

namespace Vanilo\Checkout;

use Illuminate\Support\Traits\ForwardsCalls;
use Vanilo\Checkout\Contracts\Checkout as CheckoutContract;
use Vanilo\Checkout\Contracts\CheckoutState as CheckoutStateContract;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Contracts\CheckoutSubject;

/** @todo Remove `ArrayAccess` and make the Checkout interface to extend ArrayAccess in v4 */
class CheckoutManager implements CheckoutContract, \ArrayAccess
{
    use ForwardsCalls;

    /** @var  CheckoutStore */
    protected $store;

    public function __construct(CheckoutStore $store)
    {
        $this->store = $store;
    }

    public function __call(string $method, array $arguments)
    {
        return $this->forwardDecoratedCallTo($this->store, $method, $arguments);
    }

    public function __get(string $name)
    {
        return $this->store->{$name};
    }

    /**
     * @inheritDoc
     */
    public function getCart()
    {
        return $this->store->getCart();
    }

    /**
     * @inheritDoc
     */
    public function setCart(CheckoutSubject $cart)
    {
        $this->store->setCart($cart);
    }

    /**
     * @inheritdoc
     */
    public function getState(): CheckoutStateContract
    {
        return $this->store->getState();
    }

    /**
     * @inheritdoc
     */
    public function setState($state)
    {
        $this->store->setState($state);
    }

    /**
     * @inheritdoc
     */
    public function getBillpayer(): Billpayer
    {
        return $this->store->getBillpayer();
    }

    /**
     * @inheritdoc
     */
    public function setBillpayer(Billpayer $billpayer)
    {
        return $this->store->setBillpayer($billpayer);
    }

    /**
     * @inheritdoc
     */
    public function getShippingAddress(): Address
    {
        return $this->store->getShippingAddress();
    }

    /**
     * @inheritDoc
     */
    public function setShippingAddress(Address $address)
    {
        $this->store->setShippingAddress($address);
    }

    public function setCustomAttribute(string $key, $value): void
    {
        $this->store->setCustomAttribute($key, $value);
    }

    public function getCustomAttribute(string $key)
    {
        return $this->store->getCustomAttribute($key);
    }

    public function putCustomAttributes(array $data): void
    {
        $this->store->putCustomAttributes($data);
    }

    public function getCustomAttributes(): array
    {
        return $this->store->getCustomAttributes();
    }

    /**
     * @inheritdoc
     */
    public function update(array $data)
    {
        $this->store->update($data);
    }

    /**
     * @inheritDoc
     */
    public function total()
    {
        return $this->store->total();
    }

    public function offsetExists(mixed $offset)
    {
        return $this->store->offsetExists($offset);
    }

    public function offsetGet(mixed $offset)
    {
        return $this->store->offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value)
    {
        $this->store->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset)
    {
        $this->store->offsetUnset($offset);
    }
}
