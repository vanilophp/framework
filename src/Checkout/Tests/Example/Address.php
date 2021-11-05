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

use Vanilo\Contracts\Address as AddressContract;

class Address implements AddressContract
{
    protected $data;

    public function __construct(array $data = null)
    {
        $this->data = $data ?: [];
    }

    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
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
}
