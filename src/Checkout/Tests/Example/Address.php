<?php

declare(strict_types=1);

/**
 * Contains the Address class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */

namespace Vanilo\Checkout\Tests\Example;

use Illuminate\Contracts\Support\Arrayable;
use Konekt\Address\Models\Country;
use Konekt\Address\Models\Province;
use Vanilo\Contracts\Address as AddressContract;

class Address implements AddressContract, Arrayable
{
    protected $data;

    public function __construct(array $data = null)
    {
        $this->data = $data ?: [];
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }

    public function __get($name)
    {
        // Emulates the relationship
        if ('country' === $name && array_key_exists('country_id', $this->data)) {
            return Country::find($this->data['country_id']);
        } elseif ('province' === $name && array_key_exists('province_id', $this->data)) {
            return Province::find($this->data['province_id']);
        }

        return $this->data[$name] ?? null;
    }

    public function getName(): string
    {
        return $this->data['name'] ?? '';
    }

    public function getCountryCode(): string
    {
        return $this->data['country_code'] ?? '';
    }

    public function getProvinceCode(): ?string
    {
        return $this->data['province_code'] ?? null;
    }

    public function getPostalCode(): ?string
    {
        return $this->data['postal_code'] ?? null;
    }

    public function getCity(): ?string
    {
        return $this->data['city'] ?? null;
    }

    public function getAddress(): string
    {
        return $this->data['address'] ?? '';
    }

    public function getAddress2(): ?string
    {
        return $this->data['address2'] ?? null;
    }

    public function fill(array $attributes)
    {
        $this->data = array_merge($this->data, $attributes);
    }

    public function toArray()
    {
        return $this->data;
    }
}
