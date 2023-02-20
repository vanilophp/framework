<?php

declare(strict_types=1);

/**
 * Contains the ShipmentTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-28
 *
 */

namespace Vanilo\Shipment\Tests;

use Konekt\Enum\Enum;
use Vanilo\Shipment\Models\Carrier;
use Vanilo\Shipment\Models\Shipment;
use Vanilo\Shipment\Models\ShipmentStatus;
use Vanilo\Shipment\Tests\Dummies\Address;

class ShipmentTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_minimal_data()
    {
        $address = $this->createAddress();
        $shipment = Shipment::create(['address_id' => $address->id]);

        $this->assertInstanceOf(Shipment::class, $shipment);
    }

    /** @test */
    public function it_has_an_address()
    {
        $address = $this->createAddress();
        $shipment = Shipment::create(['address_id' => $address->id]);

        $this->assertInstanceOf(\Vanilo\Contracts\Address::class, $shipment->deliveryAddress());
    }

    /** @test */
    public function is_trackable_is_true_by_default()
    {
        $shipment = Shipment::create(['address_id' => $this->createAddress()->id])->fresh();

        $this->assertTrue($shipment->is_trackable);
    }

    /** @test */
    public function can_be_marked_as_non_trackable()
    {
        $shipment = Shipment::create(['address_id' => $this->createAddress()->id, 'is_trackable' => false])->fresh();

        $this->assertFalse($shipment->is_trackable);
    }

    /** @test */
    public function the_status_is_an_enum()
    {
        $shipment = Shipment::create(['address_id' => $this->createAddress()->id]);

        $this->assertInstanceOf(Enum::class, $shipment->status);
        $this->assertInstanceOf(ShipmentStatus::class, $shipment->status);
    }

    /** @test */
    public function the_status_is_new_by_default()
    {
        $shipment = Shipment::create(['address_id' => $this->createAddress()->id]);

        $this->assertTrue($shipment->status->is_new);
    }

    /** @test */
    public function the_weight_field_is_null_by_default()
    {
        $shipment = Shipment::create(['address_id' => $this->createAddress()->id]);

        $this->assertNull($shipment->weight);
    }

    /** @test */
    public function the_weight_field_is_a_float_if_it_has_a_value()
    {
        $shipment = Shipment::create(['address_id' => $this->createAddress()->id, 'weight' => 2.56])->fresh();

        $this->assertIsFloat($shipment->weight);
        $this->assertEquals(2.56, $shipment->weight);
    }

    /** @test */
    public function the_dimension_fields_are_null_by_default()
    {
        $shipment = Shipment::create(['address_id' => $this->createAddress()->id]);

        $this->assertNull($shipment->width);
        $this->assertNull($shipment->height);
        $this->assertNull($shipment->length);
    }

    /** @test */
    public function the_dimension_fields_are_floats_if_they_have_values()
    {
        $shipment = Shipment::create([
            'address_id' => $this->createAddress()->id,
            'width' => 1.5,
            'height' => 1.6,
            'length' => 1.7,
        ])->fresh();

        $this->assertIsFloat($shipment->width);
        $this->assertEquals(1.5, $shipment->width);
        $this->assertIsFloat($shipment->height);
        $this->assertEquals(1.6, $shipment->height);
        $this->assertIsFloat($shipment->length);
        $this->assertEquals(1.7, $shipment->length);
    }

    /** @test */
    public function the_configuration_is_an_empty_array_by_default()
    {
        $shipment = Shipment::create(['address_id' => $this->createAddress()->id]);

        $this->assertIsArray($shipment->configuration);
        $this->assertEmpty($shipment->configuration);
    }

    /** @test */
    public function the_configuration_can_be_set_as_an_array()
    {
        $shipment = Shipment::create(['address_id' => $this->createAddress()->id]);

        $shipment->configuration = ['some_key' => 'some value'];
        $shipment->save();
        $shipment = $shipment->fresh();

        $this->assertEquals('some value', $shipment->configuration['some_key']);
    }

    /** @test */
    public function it_can_have_a_carrier()
    {
        $carrier = Carrier::create(['name' => 'SEUR']);
        $shipment = Shipment::create([
            'address_id' => $this->createAddress()->id,
            'carrier_id' => $carrier->id,
        ]);

        $this->assertInstanceOf(Carrier::class, $shipment->getCarrier());
        $this->assertEquals('SEUR', $shipment->carrier->name);
        $this->assertEquals('SEUR', $shipment->getCarrier()->name());
    }

    /** @test */
    public function it_can_have_a_reference_number()
    {
        $shipment = Shipment::create([
            'address_id' => $this->createAddress()->id,
            'reference_number' => 'S799873',
        ])->fresh();

        $this->assertEquals('S799873', $shipment->reference_number);
    }

    /** @test */
    public function it_can_have_a_tracking_number()
    {
        $shipment = Shipment::create([
            'address_id' => $this->createAddress()->id,
            'tracking_number' => '44832801',
        ])->fresh();

        $this->assertEquals('44832801', $shipment->tracking_number);
    }

    private function createAddress(): Address
    {
        return Address::create([
            'name' => 'Vladimir Ilyich Lenin',
            'country_id' => 'RU',
            'address' => 'bulvar Noviy Venec d 3',
            'city' => 'Ulyanovsk',
            'postalcode' => '432063'
        ])->fresh();
    }
}
