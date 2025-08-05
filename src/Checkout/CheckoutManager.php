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

use Illuminate\Support\Collection;
use Illuminate\Support\Traits\ForwardsCalls;
use Vanilo\Checkout\Contracts\Checkout as CheckoutContract;
use Vanilo\Checkout\Contracts\CheckoutState as CheckoutStateContract;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Contracts\CheckoutSubject;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Contracts\Dimension;

class CheckoutManager implements CheckoutContract
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

    public function getCart(): ?CheckoutSubject
    {
        return $this->store->getCart();
    }

    public function setCart(CheckoutSubject $cart)
    {
        $this->store->setCart($cart);
    }

    public function getState(): CheckoutStateContract
    {
        return $this->store->getState();
    }

    public function setState($state)
    {
        $this->store->setState($state);
    }

    public function getBillpayer(): Billpayer
    {
        return $this->store->getBillpayer();
    }

    public function setBillpayer(Billpayer $billpayer)
    {
        return $this->store->setBillpayer($billpayer);
    }

    public function getShippingAddress(): ?Address
    {
        return $this->store->getShippingAddress();
    }

    public function setShippingAddress(Address $address): void
    {
        $this->store->setShippingAddress($address);
    }

    public function removeShippingAddress(): void
    {
        $this->store->removeShippingAddress();
    }

    public function getShipToBillingAddress(bool $default = true): bool
    {
        return $this->store->getShipToBillingAddress($default);
    }

    public function setShipToBillingAddress(bool $value): void
    {
        $this->store->setShipToBillingAddress($value);
    }

    public function getShippingMethodId(): null|int|string
    {
        return $this->store->getShippingMethodId();
    }

    public function setShippingMethodId(int|string|null $shippingMethodId): void
    {
        $this->store->setShippingMethodId($shippingMethodId);
    }

    public function getPaymentMethodId(): null|int|string
    {
        return $this->store->getPaymentMethodId();
    }

    public function setPaymentMethodId(int|string|null $paymentMethodId): void
    {
        $this->store->setPaymentMethodId($paymentMethodId);
    }

    public function getNotes(): ?string
    {
        return $this->store->getNotes();
    }

    public function setNotes(?string $text): void
    {
        $this->store->setNotes($text);
    }

    public function clear(): void
    {
        $this->store->clear();
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

    public function update(array $data)
    {
        $this->store->update($data);
    }

    public function total()
    {
        return $this->store->total();
    }

    public function itemsTotal(): float
    {
        return $this->getCart()->getItems()->sum('total');
    }

    public function getShippingAmount(): DetailedAmount
    {
        return $this->store->getShippingAmount();
    }

    public function setShippingAmount(float|DetailedAmount $amount): void
    {
        $this->store->setShippingAmount($amount);
    }

    public function getTaxesAmount(): DetailedAmount
    {
        return $this->store->getTaxesAmount();
    }

    public function setTaxesAmount(float|DetailedAmount $amount): void
    {
        $this->store->setTaxesAmount($amount);
    }

    public function getShippableItems(): Collection
    {
        return $this->store->getShippableItems();
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
