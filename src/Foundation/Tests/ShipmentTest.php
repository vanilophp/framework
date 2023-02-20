<?php

declare(strict_types=1);

/**
 * Contains the ShipmentTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-20
 *
 */

namespace Vanilo\Foundation\Tests;

use Illuminate\Support\Str;
use Konekt\Address\Models\Country;
use Vanilo\Foundation\Models\Address;
use Vanilo\Foundation\Models\Order;
use Vanilo\Foundation\Models\Shipment;
use Vanilo\Order\Models\OrderStatus;

class ShipmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Country::firstOrCreate(
            ['id' => 'CA'],
            ['name' => 'Canada', 'phonecode' => 1, 'is_eu_member' => false],
        );
    }

    /** @test */
    public function a_shipment_can_be_added_to_an_order()
    {
        $address = factory(Address::class)->create(['country_id' => 'CA']);
        $order = Order::create([
            'number' => Str::uuid()->getHex()->toString(),
            'status' => OrderStatus::defaultValue(),
            'shipping_address_id' => $address->id,
        ]);

        $shipment = Shipment::create(['address_id' => $address->id]);
        $order->addShipment($shipment);

        $this->assertCount(1, $order->shipments);
        $this->assertEquals($shipment->id, $order->shipments->first()->id);
    }

    /** @test */
    public function multiple_shipments_can_be_added_to_an_order()
    {
        $address = factory(Address::class)->create(['country_id' => 'CA']);
        $order = Order::create([
            'shipping_address_id' => $address->id,
            'number' => Str::uuid()->getHex()->toString(),
        ]);
        $shipment1 = Shipment::create(['address_id' => $address->id]);
        $shipment2 = Shipment::create(['address_id' => $address->id]);

        $order->addShipments($shipment1, $shipment2);

        $this->assertCount(2, $order->shipments);
        $this->assertEquals($shipment1->id, $order->shipments->first()->id);
        $this->assertEquals($shipment2->id, $order->shipments->last()->id);
    }

    /** @test */
    public function one_shipment_can_belong_to_multiple_orders()
    {
        $address = factory(Address::class)->create(['country_id' => 'CA']);
        $order1 = Order::create(['shipping_address_id' => $address->id, 'number' => Str::uuid()->getHex()->toString()]);
        $order2 = Order::create(['shipping_address_id' => $address->id, 'number' => Str::uuid()->getHex()->toString()]);
        $shipment = Shipment::create(['address_id' => $address->id]);

        $shipment->addOrder($order1);
        $shipment->addOrder($order2);

        $this->assertCount(2, $shipment->orders);
        $this->assertEquals($order1->id, $shipment->orders->first()->id);
        $this->assertEquals($order2->id, $shipment->orders->last()->id);
    }
}
