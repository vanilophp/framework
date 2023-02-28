<?php

declare(strict_types=1);

/**
 * Contains the ShippingMethodZonesTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-27
 *
 */

namespace Vanilo\Shipment\Tests;

use Konekt\Address\Models\Zone;
use Vanilo\Shipment\Models\ShippingMethod;

class ShippingMethodZonesTest extends TestCase
{
    /** @test */
    public function a_zone_can_be_assigned_to_a_shipping_method()
    {
        $zone = Zone::create(['name' => 'Maritime']);
        $method = ShippingMethod::create(['name' => 'Canada Post', 'zone_id' => $zone->id])->fresh();

        $this->assertEquals($zone->id, $method->zone_id);
        $this->assertInstanceOf(Zone::class, $method->zone);
        $this->assertEquals('Maritime', $method->zone->name);
    }

    /** @test */
    public function the_shipping_methods_available_for_a_zone_can_be_queried()
    {
        $zone = Zone::create(['name' => 'Maritime']);
        ShippingMethod::create(['name' => 'Envoi régulier (3 jours)', 'zone_id' => $zone->id]);
        ShippingMethod::create(['name' => 'Expresspost (1-2 jours)', 'zone_id' => $zone->id]);
        ShippingMethod::create(['name' => 'Avion (2 semaines)', 'is_active' => true]);

        $methods = ShippingMethod::availableOnesForZone($zone);
        $this->assertCount(2, $methods);
        $this->assertContains('Envoi régulier (3 jours)', $methods->pluck('name'));
        $this->assertContains('Expresspost (1-2 jours)', $methods->pluck('name'));
        $this->assertNotContains('Avion (2 semaines)', $methods->pluck('name'));
    }

    /** @test */
    public function the_available_shipping_method_list_for_a_zone_excludes_inactive_items()
    {
        $zone = Zone::create(['name' => 'etats-unis']);
        ShippingMethod::create(['name' => 'Envoi régulier (10 jours ouvrables)', 'zone_id' => $zone->id]);
        ShippingMethod::create(['name' => 'Expresspost (5-7 jours)', 'zone_id' => $zone->id, 'is_active' => false]);

        $methods = ShippingMethod::availableOnesForZone($zone);
        $this->assertCount(1, $methods);
        $this->assertContains('Envoi régulier (10 jours ouvrables)', $methods->pluck('name'));
        $this->assertNotContains('Expresspost (5-7 jours)', $methods->pluck('name'));
    }

    /** @test */
    public function the_shipping_methods_available_for_multiple_zonse_can_be_queried()
    {
        $europe = Zone::create(['name' => 'Europe']);
        $maritimes = Zone::create(['name' => 'Maritimes']);
        $quebecOntario = Zone::create(['name' => 'Quebec & Ontario']);
        ShippingMethod::create(['name' => 'Envoi régulier (3 jours)', 'zone_id' => $quebecOntario->id]);
        ShippingMethod::create(['name' => 'Expresspost (Délai de 1 à 2 jours ouvrables)', 'zone_id' => $quebecOntario->id]);
        ShippingMethod::create(['name' => 'Poste régulière (3 jours)', 'zone_id' => $maritimes->id]);
        ShippingMethod::create(['name' => 'Avion (2 semaines)', 'zone_id' => $europe->id]);

        $methodsForCA = ShippingMethod::availableOnesForZones($maritimes, $quebecOntario);
        $this->assertCount(3, $methodsForCA);
        $this->assertContains('Envoi régulier (3 jours)', $methodsForCA->pluck('name'));
        $this->assertContains('Expresspost (Délai de 1 à 2 jours ouvrables)', $methodsForCA->pluck('name'));
        $this->assertContains('Poste régulière (3 jours)', $methodsForCA->pluck('name'));
        $this->assertNotContains('Avion (2 semaines)', $methodsForCA->pluck('name'));

        $methodsForEU = ShippingMethod::availableOnesForZones($europe);
        $this->assertCount(1, $methodsForEU);
        $this->assertContains('Avion (2 semaines)', $methodsForEU->pluck('name'));
    }

    /** @test */
    public function the_for_zones_scope_accepts_an_array_of_zone_ids()
    {
        $zone1 = Zone::create(['name' => 'Z1']);
        $zone2 = Zone::create(['name' => 'Z2']);
        ShippingMethod::create(['name' => 'SM1', 'zone_id' => $zone1->id]);
        ShippingMethod::create(['name' => 'SM2']);
        ShippingMethod::create(['name' => 'SM3', 'zone_id' => $zone2->id]);
        ShippingMethod::create(['name' => 'SM4', 'zone_id' => $zone1->id]);

        $methods = ShippingMethod::forZones([$zone1->id, $zone2->id])->get();
        $this->assertCount(3, $methods);
        $this->assertContains('SM1', $methods->pluck('name'));
        $this->assertContains('SM3', $methods->pluck('name'));
        $this->assertContains('SM4', $methods->pluck('name'));
        $this->assertNotContains('SM2', $methods->pluck('name'));
    }

    /** @test */
    public function the_for_zones_scope_accepts_an_array_of_zone_models()
    {
        $zone3 = Zone::create(['name' => 'Z3']);
        $zone4 = Zone::create(['name' => 'Z4']);
        $zone5 = Zone::create(['name' => 'Z4']);
        ShippingMethod::create(['name' => 'SM5', 'zone_id' => $zone5->id]);
        ShippingMethod::create(['name' => 'SM6', 'zone_id' => $zone3->id]);
        ShippingMethod::create(['name' => 'SM7']);
        ShippingMethod::create(['name' => 'SM8', 'zone_id' => $zone4->id]);
        ShippingMethod::create(['name' => 'SM9', 'zone_id' => $zone4->id]);
        ShippingMethod::create(['name' => 'SM10', 'zone_id' => $zone4->id]);

        $methods = ShippingMethod::forZones([$zone3, $zone4])->get();
        $this->assertCount(4, $methods);
        $this->assertContains('SM6', $methods->pluck('name'));
        $this->assertContains('SM8', $methods->pluck('name'));
        $this->assertContains('SM9', $methods->pluck('name'));
        $this->assertContains('SM10', $methods->pluck('name'));
        $this->assertNotContains('SM5', $methods->pluck('name'));
        $this->assertNotContains('SM7', $methods->pluck('name'));
    }

    /** @test */
    public function the_for_zones_scope_accepts_a_collection_of_zone_models()
    {
        $zone7 = Zone::create(['name' => 'Z7']);
        $zone8 = Zone::create(['name' => 'Z8']);
        ShippingMethod::create(['name' => 'SM11', 'zone_id' => $zone8->id]);
        ShippingMethod::create(['name' => 'SM12', 'zone_id' => $zone7->id]);
        ShippingMethod::create(['name' => 'SM13']);

        $methods = ShippingMethod::forZones(collect([$zone7, $zone8]))->get();
        $this->assertCount(2, $methods);
        $this->assertContains('SM11', $methods->pluck('name'));
        $this->assertContains('SM12', $methods->pluck('name'));
        $this->assertNotContains('SM13', $methods->pluck('name'));
    }
}
