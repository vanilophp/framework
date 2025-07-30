<?php

declare(strict_types=1);

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

use Konekt\Address\Models\Country;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Contracts\Address as AddressContract;
use Vanilo\Order\Models\Billpayer;
use Vanilo\Order\Models\Order;
use Vanilo\Order\Tests\Dummies\Address;

class AddressesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Country::create([
            'id' => 'DK',
            'name' => 'Denmark',
            'phonecode' => 45,
            'is_eu_member' => 1
        ]);

        Country::create([
            'id' => 'DE',
            'name' => 'Germany',
            'phonecode' => 49,
            'is_eu_member' => 1
        ]);
    }

    #[Test] public function billing_and_shipping_addresses_implement_vanilo_address()
    {
        $order = Order::create([
            'number' => 'OXC904'
        ]);

        $billpayer = new Billpayer();

        $billpayer
            ->address()
            ->associate(Address::create(
                [
                'name' => 'Karen Blixen',
                'country_id' => 'DK',
                'postalcode' => '2960',
                'city' => 'Rungsted',
                'address' => 'Strandvej 111'
            ]
            ));
        $order->billpayer()->associate($billpayer);
        $billpayer->save();

        $order->shippingAddress()->associate(Address::create([
            'name' => 'Karen Blixen',
            'country_id' => 'DK',
            'postalcode' => '2960',
            'city' => 'Rungsted',
            'address' => 'Strandvej 111'
        ]));

        $order->save();

        $this->assertInstanceOf(AddressContract::class, $order->billpayer->address);
        $this->assertInstanceOf(AddressContract::class, $order->shippingAddress);
    }

    #[Test] public function billing_address_data_can_be_saved()
    {
        $order = Order::create([
            'number' => 'OXC905'
        ]);

        $billpayer = new Billpayer();

        $billpayer
            ->address()
            ->associate(Address::create(
                [
                    'name' => 'Karen Blixen',
                    'country_id' => 'DK',
                    'postalcode' => '2960',
                    'city' => 'Rungsted',
                    'address' => 'Strandvej 111'
                ]
            ));
        $billpayer->save();

        $order->billpayer()->associate($billpayer);
        $order->save();

        /** @var \Vanilo\Contracts\Address $billingAddress */
        $billingAddress = $order->getBillpayer()->getBillingAddress();
        $this->assertEquals('Karen Blixen', $billingAddress->getName());
        $this->assertEquals('DK', $billingAddress->getCountryCode());
        $this->assertEquals('2960', $billingAddress->getPostalCode());
        $this->assertEquals('Rungsted', $billingAddress->getCity());
        $this->assertEquals('Strandvej 111', $billingAddress->getAddress());

        // Refetching from db
        $order = $order->fresh();

        $billingAddress = $order->getBillpayer()->getBillingAddress();
        $this->assertEquals('Karen Blixen', $billingAddress->getName());
        $this->assertEquals('DK', $billingAddress->getCountryCode());
        $this->assertEquals('2960', $billingAddress->getPostalCode());
        $this->assertEquals('Rungsted', $billingAddress->getCity());
        $this->assertEquals('Strandvej 111', $billingAddress->getAddress());
    }

    #[Test] public function shipping_address_data_can_be_saved()
    {
        $order = Order::create([
            'number' => 'OXC905'
        ]);

        $order->shippingAddress()->associate(Address::create([
            'name' => 'Katarina Witt',
            'country_id' => 'DE',
            'postalcode' => '13591',
            'city' => 'Staaken',
            'address' => 'Torweg 91'
        ]));

        $order->save();

        /** @var \Vanilo\Contracts\Address $shippingAddress */
        $shippingAddress = $order->getShippingAddress();
        $this->assertEquals('Katarina Witt', $shippingAddress->getName());
        $this->assertEquals('DE', $shippingAddress->getCountryCode());
        $this->assertEquals('13591', $shippingAddress->getPostalCode());
        $this->assertEquals('Staaken', $shippingAddress->getCity());
        $this->assertEquals('Torweg 91', $shippingAddress->getAddress());

        // Refetching from db
        $order = $order->fresh();

        $shippingAddress = $order->getShippingAddress();
        $this->assertEquals('Katarina Witt', $shippingAddress->getName());
        $this->assertEquals('DE', $shippingAddress->getCountryCode());
        $this->assertEquals('13591', $shippingAddress->getPostalCode());
        $this->assertEquals('Staaken', $shippingAddress->getCity());
        $this->assertEquals('Torweg 91', $shippingAddress->getAddress());
    }
}
