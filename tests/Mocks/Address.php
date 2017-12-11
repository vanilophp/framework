<?php
/**
 * Contains the Address class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */


namespace Vanilo\Checkout\Tests\Mocks;


use Vanilo\Contracts\Address as AddressContract;

class Address implements AddressContract
{
    protected $data;

    public function __construct(array $data = null)
    {
        $this->data = $data ?: [];
    }

    public function getName()
    {
        return $this->data['name'] ?? null;
    }

    public function getCountryCode()
    {
        return $this->data['country_code'] ?? null;
    }

    public function getProvinceCode()
    {
        return $this->data['province_code'] ?? null;
    }

    public function getPostalCode()
    {
        return $this->data['postal_code'] ?? null;
    }

    public function getCity()
    {
        return $this->data['city'] ?? null;
    }

    public function getAddress()
    {
        return $this->data['address'] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function __set($name, $value)
    {
        $this->data[$name] = $value;
    }
}
