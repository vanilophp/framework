<?php
/**
 * Contains the AddressesTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-29
 *
 */


namespace Vanilo\Order\Tests;


use Vanilo\Contracts\Address as AddressContract;
use Vanilo\Order\Models\Mocks\Address;
use Vanilo\Order\Models\Order;

class AddressesTest extends TestCase
{
    /**
     * @test
     */
    public function billing_address_implements_vanilo_address()
    {
        $order = Order::create([
            'number' => 'OXC904'
        ]);

        $order->billingAddress()->save(Address::create([
            'name'       => 'Karen Blixen',
            'country_id' => 'DK',
            'postalcode' => '2960',
            'city'       => 'Rungsted',
            'address'    => 'Strandvej 111'
        ]));

        $this->assertInstanceOf(AddressContract::class, $order->billingAddress);
    }
}
