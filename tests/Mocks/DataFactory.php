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


namespace Vanilo\Checkout\Tests\Mocks;


use Vanilo\Checkout\Contracts\CheckoutDataFactory;
use Vanilo\Checkout\Tests\Mocks\Address as MockAddress;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\BillPayer;

class DataFactory implements CheckoutDataFactory
{
    public function createBillPayer(): BillPayer
    {
        return new \Vanilo\Checkout\Tests\Mocks\BillPayer();

    }

    public function createShippingAddress(): Address
    {
        return new MockAddress();
    }


}