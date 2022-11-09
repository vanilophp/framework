<?php

declare(strict_types=1);

/**
 * Contains the SessionStore class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-03-30
 *
 */

namespace Vanilo\Checkout\Drivers;

use Illuminate\Contracts\Session\Session;
use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Contracts\CheckoutState;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Checkout\Models\CheckoutStateProxy;
use Vanilo\Checkout\Traits\HasCart;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;

class SessionStore implements CheckoutStore
{
    use HasCart;

    private const DEFAULT_PREFIX = 'vanilo_checkout__';

    protected Session $session;

    protected string $prefix;

    protected CheckoutDataFactory $factory;

    public function __construct(CheckoutDataFactory $factory, Session $session = null, string $prefix = null)
    {
        $this->session = $session ?? app('session.store');
        $this->prefix = $prefix ?? static::DEFAULT_PREFIX;
        $this->factory = $factory;
    }

    public function getState(): CheckoutState
    {
        $rawState = $this->retrieveData('state');

        return $rawState instanceof CheckoutState ? $rawState : CheckoutStateProxy::create($rawState);
    }

    public function setState($state)
    {
        $this->storeData('state', $state instanceof CheckoutState ? $state->value() : $state);
    }

    public function getBillpayer(): Billpayer
    {
        return $this->retrieveData('billpayer') ?? $this->factory->createBillpayer();
    }

    public function setBillpayer(Billpayer $billpayer)
    {
        $this->storeData('billpayer', $billpayer);
    }

    public function getShippingAddress(): Address
    {
        return $this->retrieveData('shipping_address') ?? $this->factory->createShippingAddress();
    }

    public function setShippingAddress(Address $address)
    {
        $this->storeData('shipping_address', $address);
    }

    public function setCustomAttribute(string $key, $value): void
    {
        $this->storeData("custom_attr__{$key}", $value);
    }

    public function getCustomAttribute(string $key)
    {
        return $this->retrieveData("custom_attr__{$key}");
    }

    public function putCustomAttributes(array $data): void
    {
        // TODO: Implement putCustomAttributes() method.
    }

    public function getCustomAttributes(): array
    {
        // TODO: Implement getCustomAttributes() method.
    }

    public function update(array $data)
    {
        // TODO: Implement update() method.
    }

    public function total()
    {
        return $this->cart->total();
    }

    protected function storeData(string $key, mixed $value): void
    {
        $this->session->put($this->prefix . $key, $value);
    }

    protected function retrieveData(string $key): mixed
    {
        return $this->session->get($this->prefix . $key);
    }
}
