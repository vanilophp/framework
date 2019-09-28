<?php
/**
 * Contains the DataFactory class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-12-04
 *
 */

namespace Vanilo\Checkout\Tests\Example;

use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Tests\Example\Address as MockAddress;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;

class DataFactory implements CheckoutDataFactory
{
    public function createBillPayer(): Billpayer
    {
        return new \Vanilo\Checkout\Tests\Example\Billpayer();
    }

    public function createShippingAddress(): Address
    {
        return new MockAddress();
    }
}
