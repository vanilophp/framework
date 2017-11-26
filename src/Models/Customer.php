<?php
/**
 * Contains the Customer class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */


namespace Vanilo\Framework\Models;


use Konekt\Customer\Models\Customer as BaseCustomer;
use Vanilo\Contracts\Customer as CustomerContract;

class Customer extends BaseCustomer implements CustomerContract
{
    protected $enums = [
        'type' => 'Konekt\Customer\Models\CustomerTypeProxy@enumClass'
    ];

    /**
     * @inheritDoc
     */
    public function getFirstName()
    {
        return $this->firstname;
    }

    /**
     * @inheritDoc
     */
    public function getLastName()
    {
        return $this->lastname;
    }

    /**
     * @inheritDoc
     */
    public function getCompanyName()
    {
        return $this->company_name;
    }

    /**
     * @inheritDoc
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @inheritDoc
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @inheritDoc
     */
    public function getTaxNumber()
    {
        return $this->tax_nr;
    }
}
