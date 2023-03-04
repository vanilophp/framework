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
use Illuminate\Contracts\Support\Arrayable;
use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Contracts\Address;

class SessionStore extends BaseCheckoutStore
{
    protected const DEFAULT_PREFIX = 'vanilo_checkout__';

    protected const CUSTOM_ATTRIBUTES_KEY = '__custom_attributes';

    protected Session $session;

    protected string $prefix;

    public function __construct(CheckoutDataFactory $factory, Session $session = null, string $prefix = null)
    {
        parent::__construct($factory);
        $this->session = $session ?? app('session.store');
        $this->prefix = $prefix ?? static::DEFAULT_PREFIX;
    }

    public function getShippingAddress(): Address
    {
        $result = $this->factory->createShippingAddress();
        if (is_array($rawData = $this->readRawDataFromStore('shipping_address'))) {
            $this->fill($result, $rawData);
        }

        return $result;
    }

    public function setShippingAddress(Address $address)
    {
        $this->writeRawDataToStore('shipping_address', $address);
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
        $this->writeRawDataToStore(static::CUSTOM_ATTRIBUTES_KEY, $data);
    }

    public function getCustomAttributes(): array
    {
        return $this->readRawDataFromStore(static::CUSTOM_ATTRIBUTES_KEY) ?? [];
    }

    public function total()
    {
        return $this->cart->total();
    }

    public function offsetExists(mixed $offset)
    {
        if ($this->isAnAliasAttribute($offset)) {
            $offset = $this->getTargetOfAlias($offset);
        }

        return in_array($offset, array_merge($this->attributesPlain, $this->attributesViaGetterSetter));
    }

    public function offsetUnset(mixed $offset)
    {
        $this->session->forget($offset);
    }

    public function clear(): void
    {
        $this->session->forget($this->prefix);
    }

    protected function readRawDataFromStore(string $key, $default = null): mixed
    {
        return $this->session->get($this->prefix . '.' . $key, $default);
    }

    protected function writeRawDataToStore(string $key, mixed $data): void
    {
        $normalizedData = $data;
        if (!is_scalar($data) && $data instanceof Arrayable) {
            $normalizedData = $data->toArray();
        }

        $this->session->put($this->prefix . '.' . $key, $normalizedData);
    }
}
