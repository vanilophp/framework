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
use Vanilo\Contracts\BillingSubject;

class DataFactory implements CheckoutDataFactory
{
    public function createBillingSubject(): BillingSubject
    {

    }

    public function createShippingAddress(): Address
    {
        return new MockAddress();
    }


}