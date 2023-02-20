<?php

declare(strict_types=1);

/**
 * Contains the ShippablesTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-20
 *
 */

namespace Vanilo\Shipment\Tests;

use Vanilo\Shipment\Models\Shipment;
use Vanilo\Shipment\Tests\Dummies\Address;
use Vanilo\Shipment\Tests\Dummies\ShippableDummyOrder;

class ShippablesTest extends TestCase
{
    /** @test */
    public function a_shipment_can_be_assigned_to_a_single_shippable()
    {
        $address = $this->createAddress();
        $order = ShippableDummyOrder::create(['address_id' => $address->id]);
        $shipment = Shipment::create(['address_id' => $address->id]);

        $order->shipments()->save($shipment);

        $this->assertCount(1, $order->shipments);
        $this->assertEquals($shipment->id, $order->shipments->first()->id);
    }

    /** @test */
    public function multiple_shipments_can_be_assigned_to_a_single_shippable()
    {
        $address = $this->createAddress();
        $order = ShippableDummyOrder::create(['address_id' => $address->id]);
        $shipment1 = Shipment::create(['address_id' => $address->id]);
        $shipment2 = Shipment::create(['address_id' => $address->id]);

        $order->shipments()->saveMany([$shipment1, $shipment2]);

        $this->assertCount(2, $order->shipments);
        $this->assertEquals($shipment1->id, $order->shipments->first()->id);
        $this->assertEquals($shipment2->id, $order->shipments->last()->id);
    }

    /** @test */
    public function one_shipment_can_belong_to_multiple_shippables()
    {
        Shipment::resolveRelationUsing('orders', function (Shipment $shipment) {
            return $shipment->morphedByMany(ShippableDummyOrder::class, 'shippable');
        });

        $address = $this->createAddress();
        $order1 = ShippableDummyOrder::create(['address_id' => $address->id]);
        $order2 = ShippableDummyOrder::create(['address_id' => $address->id]);
        $shipment = Shipment::create(['address_id' => $address->id]);

        $shipment->orders()->saveMany([$order1, $order2]);

        $this->assertCount(2, $shipment->orders);
        $this->assertEquals($order1->id, $shipment->orders->first()->id);
        $this->assertEquals($order2->id, $shipment->orders->last()->id);
    }

    private function createAddress(): Address
    {
        return Address::create([
            'name' => 'Jānis Endzelīns',
            'country_id' => 'LV',
            'address' => 'Blaumaņa iela 30A',
            'city' => 'Koknese',
            'postalcode' => '5113'
        ])->fresh();
    }
}
