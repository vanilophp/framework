<?php
/**
 * Contains the CheckoutDataFactory class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */


namespace Vanilo\Framework\Factories;


use Konekt\Address\Contracts\Address as AddressContract;
use Konekt\Address\Models\AddressType;
use Konekt\Customer\Contracts\Customer as CustomerContract;
use Vanilo\Checkout\Contracts\CheckoutDataFactory as CheckoutDataFactoryContract;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Customer;

class CheckoutDataFactory implements CheckoutDataFactoryContract
{
    public function createBillingAddress(): Address
    {
        $address = app(AddressContract::class);
        $address->type = AddressType::BILLING;

        return $address;
    }

    public function createShippingAddress(): Address
    {
        $address = app(AddressContract::class);
        $address->type = AddressType::SHIPPING;

        return $address;
    }

    public function createCustomer(): Customer
    {
        return app(CustomerContract::class);
    }
}
