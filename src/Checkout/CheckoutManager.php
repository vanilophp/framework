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
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Contracts\Dimension;
use Vanilo\Contracts\Shippable;

/** @todo Remove `ArrayAccess` and make the Checkout interface to extend ArrayAccess in v4 */
/** @todo Remove `Shippable` and make the Checkout interface to extend Shippable in v4 */
class CheckoutManager implements CheckoutContract, Shippable, \ArrayAccess
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

    /** @todo add this to the interface in v4 */
    public function itemsTotal(): float
    {
        return $this->getCart()->getItems()->sum('total');
    }

    /** @todo add this to the interface in v4 */
    public function getShippingAmount(): DetailedAmount
    {
        return $this->store->getShippingAmount();
    }

    /** @todo add this to the interface in v4 */
    public function setShippingAmount(float|DetailedAmount $amount): void
    {
        $this->store->setShippingAmount($amount);
    }

    /** @todo add this to the interface in v4 */
    public function getTaxesAmount(): DetailedAmount
    {
        return $this->store->getTaxesAmount();
    }

    /** @todo add this to the interface in v4 */
    public function setTaxesAmount(float|DetailedAmount $amount): void
    {
        $this->store->setTaxesAmount($amount);
    }

    public function weight(): float
    {
        return $this->store->weight();
    }

    public function weightUnit(): string
    {
        return $this->store->weightUnit();
    }

    public function dimension(): ?Dimension
    {
        return $this->store->dimension();
    }

    public function offsetExists(mixed $offset): bool
    {
        return $this->store->offsetExists($offset);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->store->offsetGet($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->store->offsetSet($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        $this->store->offsetUnset($offset);
    }
}
