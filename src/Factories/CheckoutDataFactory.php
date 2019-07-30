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
use Vanilo\Checkout\Contracts\CheckoutDataFactory as CheckoutDataFactoryContract;
use Vanilo\Contracts\Address;
use Vanilo\Contracts\Billpayer;
use Vanilo\Order\Contracts\Billpayer as BillpayerContract;

class CheckoutDataFactory implements CheckoutDataFactoryContract
{
    public function createBillpayer(): Billpayer
    {
        $billpayer = app(BillpayerContract::class);

        $address       = app(AddressContract::class);
        $address->type = AddressType::BILLING;

        $billpayer->address()->associate($address);

        return $billpayer;
    }

    public function createShippingAddress(): Address
    {
        $address       = app(AddressContract::class);
        $address->type = AddressType::SHIPPING;

        return $address;
    }
}
