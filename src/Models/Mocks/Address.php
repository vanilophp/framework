<?php
/**
 * Contains the Address mock class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-29
 *
 */


namespace Vanilo\Order\Models\Mocks;


use Illuminate\Database\Eloquent\Model;
use Vanilo\Contracts\Address as AddressContract;

class Address extends Model implements AddressContract
{
    protected $guarded = ['id'];

    public function getName()
    {
        return $this->name;
    }

    public function getCountryCode()
    {
        return $this->country_id;
    }

    public function getProvinceCode()
    {
        return $this->province;
    }

    public function getPostalCode()
    {
        return $this->postalcode;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getAddress()
    {
        return $this->address;
    }


}