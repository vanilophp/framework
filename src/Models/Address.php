<?php
/**
 * Contains the Address class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */


namespace Vanilo\Framework\Models;


use Konekt\Address\Models\Address as BaseAddress;
use Vanilo\Contracts\Address as AddressContract;

class Address extends BaseAddress implements AddressContract
{
    /**
     * @inheritDoc
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function getCountryCode()
    {
        return $this->country_id;
    }

    /**
     * @inheritDoc
     */
    public function getProvinceCode()
    {
        return $this->province ? $this->province->code : null;
    }

    /**
     * @inheritDoc
     */
    public function getPostalCode()
    {
        return $this->postalcode;
    }

    /**
     * @inheritDoc
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @inheritDoc
     */
    public function getAddress()
    {
        return $this->address;
    }
}
