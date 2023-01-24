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
use Illuminate\Support\Arr;
use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Contracts\CheckoutState;
use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Checkout\Models\CheckoutStateProxy;
use Vanilo\Checkout\Traits\ComputesShipToName;
use Vanilo\Checkout\Traits\EmulatesFillAttributes;
use Vanilo\Checkout\Traits\HasCart;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;

class SessionStore implements CheckoutStore
{
    use HasCart;
    use EmulatesFillAttributes;
    use ComputesShipToName;

    protected const DEFAULT_PREFIX = 'vanilo_checkout__';

    protected const CUSTOM_ATTRIBUTES_KEY = '__custom_attributes';

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
        $customAttributes = $this->getCustomAttributes();
        $customAttributes[$key] = $value;

        $this->putCustomAttributes($customAttributes);
    }

    public function getCustomAttribute(string $key)
    {
        return $this->getCustomAttributes()[$key] ?? null;
    }

    public function putCustomAttributes(array $data): void
    {
        $this->storeData(static::CUSTOM_ATTRIBUTES_KEY, $data);
    }

    public function getCustomAttributes(): array
    {
        return $this->retrieveData(static::CUSTOM_ATTRIBUTES_KEY) ?? [];
    }

    public function update(array $data)
    {
        if (isset($data['billpayer'])) {
            $this->updateBillpayer($data['billpayer'] ??  []);
        }

        if (Arr::get($data, 'ship_to_billing_address')) {
            $shippingAddress = $data['billpayer']['address'];
            $shippingAddress['name'] = $this->getShipToName($this->getBillpayer());
        } else {
            $shippingAddress = $data['shipping_address'] ?? ($data['shippingAddress'] ?? []);
        }

        $this->updateShippingAddress($shippingAddress);

        foreach (Arr::except($data, ['billpayer', 'ship_to_billing_address', 'shipping_address', 'shippingAddress']) as $key => $value) {
            $this->setCustomAttribute($key, $value);
        }
    }

    public function total()
    {
        return $this->cart->total();
    }

    protected function updateBillpayer($data)
    {
        $billpayer = $this->getBillpayer();
        $this->fill($billpayer, Arr::except($data, 'address'));
        $this->fill($billpayer->address, $data['address']);

        $this->setBillpayer($billpayer);
    }

    protected function updateShippingAddress($data)
    {
        $shippingAddress = $this->getShippingAddress();
        $this->fill($shippingAddress, $data);
        $this->setShippingAddress($shippingAddress);
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
