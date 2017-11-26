<?php
/**
 * Contains the CheckoutDataFactory interface.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-26
 *
 */


namespace Vanilo\Checkout\Contracts;


use Vanilo\Contracts\Address;
use Vanilo\Contracts\Customer;

interface CheckoutDataFactory
{
    public function createBillingAddress(): Address;

    public function createShippingAddress(): Address;

    public function createCustomer(): Customer;
}
