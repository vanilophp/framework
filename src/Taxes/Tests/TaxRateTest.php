<?php

declare(strict_types=1);

/**
 * Contains the TaxRateTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-26
 *
 */

namespace Vanilo\Taxes\Tests;

use Konekt\Address\Models\Zone;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Taxes\Models\TaxCategory;
use Vanilo\Taxes\Models\TaxRate;

class TaxRateTest extends TestCase
{
    #[Test] public function it_can_be_created_with_minimal_data()
    {
        $rate = TaxRate::create(['name' => 'Manitoba HST', 'rate' => 7]);

        $this->assertInstanceOf(TaxRate::class, $rate);
        $this->assertEquals('Manitoba HST', $rate->getName());
        $this->assertEquals(7, $rate->getRate());
    }

    #[Test] public function a_tax_category_can_be_assigned_to_it()
    {
        $standard = TaxCategory::create(['name' => 'Standard']);
        $rate = TaxRate::create([
            'name' => '19%',
            'rate' => 19,
            'tax_category_id' => $standard->id,
        ]);

        $this->assertInstanceOf(TaxRate::class, $rate);
        $this->assertEquals($standard->id, $rate->tax_category_id);
        $this->assertEquals($standard->id, $rate->taxCategory->id);
        $this->assertEquals($standard->name, $rate->taxCategory->name);
    }

    #[Test] public function the_configuration_is_an_empty_array_by_default()
    {
        $rate = TaxRate::create(['name' => 'VAT', 'rate' => 25]);

        $this->assertIsArray($rate->configuration);
        $this->assertEmpty($rate->configuration);
    }

    #[Test] public function the_configuration_can_be_set_as_an_array()
    {
        $rate = TaxRate::create(['name' => 'BC Sales Tax', 'rate' => 12]);

        $rate->configuration = ['pst' => 7, 'gst' => 5];
        $rate->save();
        $rate = $rate->fresh();

        $this->assertEquals(7, $rate->configuration['pst']);
        $this->assertEquals(5, $rate->configuration['gst']);
    }

    #[Test] public function it_is_active_by_default()
    {
        $rate = TaxRate::create(['name' => 'NS Sales Tax', 'rate' => 15])->fresh();

        $this->assertTrue($rate->is_active);
    }

    #[Test] public function it_can_be_set_to_inactive()
    {
        $rate = TaxRate::create(['name' => 'UK EU VAT', 'is_active' => false, 'rate' => 17])->fresh();

        $this->assertFalse($rate->is_active);
    }

    #[Test] public function active_ones_can_be_listed()
    {
        TaxRate::create(['name' => 'Rate 1', 'is_active' => true, 'rate' => 1]);
        TaxRate::create(['name' => 'Rate 2', 'is_active' => false, 'rate' => 1]);
        TaxRate::create(['name' => 'Rate 3', 'is_active' => false, 'rate' => 1]);
        TaxRate::create(['name' => 'Rate 4', 'is_active' => true, 'rate' => 1]);
        TaxRate::create(['name' => 'Rate 5', 'is_active' => true, 'rate' => 1]);
        TaxRate::create(['name' => 'Rate 6', 'is_active' => true, 'rate' => 1]);

        $this->assertCount(4, TaxRate::actives()->get());
    }

    #[Test] public function inactive_ones_can_be_listed()
    {
        TaxRate::create(['name' => 'Rate A', 'is_active' => true, 'rate' => 1]);
        TaxRate::create(['name' => 'Rate B', 'is_active' => false, 'rate' => 1]);
        TaxRate::create(['name' => 'Rate C', 'is_active' => false, 'rate' => 1]);

        $this->assertCount(2, TaxRate::inactives()->get());
    }

    #[Test] public function a_zone_can_be_assigned_to_a_tax_rate()
    {
        $zone = Zone::create(['name' => 'Maritime']);
        $rate = TaxRate::create(['name' => '15% HST', 'zone_id' => $zone->id, 'rate' => 15])->fresh();

        $this->assertEquals($zone->id, $rate->zone_id);
        $this->assertInstanceOf(Zone::class, $rate->zone);
        $this->assertEquals('Maritime', $rate->zone->name);
    }

    #[Test] public function the_tax_rates_available_for_a_zone_can_be_queried()
    {
        $zone = Zone::create(['name' => 'Maritime']);
        TaxRate::create(['name' => '5% for physical books', 'zone_id' => $zone->id, 'rate' => 5]);
        TaxRate::create(['name' => '15%', 'zone_id' => $zone->id, 'rate' => 5]);
        TaxRate::create(['name' => 'Somewhere else', 'rate' => 19]);

        $rates = TaxRate::availableOnesForZone($zone);
        $this->assertCount(2, $rates);
        $this->assertContains('5% for physical books', $rates->pluck('name'));
        $this->assertContains('15%', $rates->pluck('name'));
        $this->assertNotContains('Somewhere else', $rates->pluck('name'));
    }

    #[Test] public function the_available_tax_rate_list_for_a_zone_excludes_inactive_items()
    {
        $zone = Zone::create(['name' => 'Newfoundland and Labrador']);
        TaxRate::create(['name' => '8% for physical books', 'zone_id' => $zone->id, 'rate' => 8]);
        TaxRate::create(['name' => '15%', 'zone_id' => $zone->id, 'rate' => 5]);
        TaxRate::create(['name' => 'Some outdated rate', 'zone_id' => $zone->id, 'rate' => 7, 'is_active' => false]);

        $rates = TaxRate::availableOnesForZone($zone);
        $this->assertCount(2, $rates);
        $this->assertContains('8% for physical books', $rates->pluck('name'));
        $this->assertContains('15%', $rates->pluck('name'));
        $this->assertNotContains('Some outdated rate', $rates->pluck('name'));
    }

    #[Test] public function the_tax_rates_available_for_multiple_zonse_can_be_queried()
    {
        $europe = Zone::create(['name' => 'Europe']);
        $maritimes = Zone::create(['name' => 'Maritimes']);
        $quebecOntario = Zone::create(['name' => 'Quebec & Ontario']);
        TaxRate::create(['name' => 'QO Rate', 'zone_id' => $quebecOntario->id, 'rate' => 1]);
        TaxRate::create(['name' => 'QO Rate 2', 'zone_id' => $quebecOntario->id, 'rate' => 1]);
        TaxRate::create(['name' => 'MT Rate', 'zone_id' => $maritimes->id, 'rate' => 1]);
        TaxRate::create(['name' => 'EU Rate', 'zone_id' => $europe->id, 'rate' => 1]);

        $ratesForCA = TaxRate::availableOnesForZones($maritimes, $quebecOntario);
        $this->assertCount(3, $ratesForCA);
        $this->assertContains('QO Rate', $ratesForCA->pluck('name'));
        $this->assertContains('QO Rate 2', $ratesForCA->pluck('name'));
        $this->assertContains('MT Rate', $ratesForCA->pluck('name'));
        $this->assertNotContains('EU Rate', $ratesForCA->pluck('name'));

        $ratesForEU = TaxRate::availableOnesForZones($europe);
        $this->assertCount(1, $ratesForEU);
        $this->assertContains('EU Rate', $ratesForEU->pluck('name'));
    }

    #[Test] public function the_for_zones_scope_accepts_an_array_of_zone_ids()
    {
        $zoneA = Zone::create(['name' => 'Zone A']);
        $zoneB = Zone::create(['name' => 'Zone B']);
        TaxRate::create(['name' => 'TR1', 'zone_id' => $zoneA->id, 'rate' => 9]);
        TaxRate::create(['name' => 'TR2', 'rate' => 9]);
        TaxRate::create(['name' => 'TR3', 'zone_id' => $zoneB->id, 'rate' => 9]);
        TaxRate::create(['name' => 'TR4', 'zone_id' => $zoneA->id, 'rate' => 9]);

        $methods = TaxRate::forZones([$zoneA->id, $zoneB->id])->get();
        $this->assertCount(3, $methods);
        $this->assertContains('TR1', $methods->pluck('name'));
        $this->assertContains('TR3', $methods->pluck('name'));
        $this->assertContains('TR4', $methods->pluck('name'));
        $this->assertNotContains('TR2', $methods->pluck('name'));
    }

    #[Test] public function the_for_zones_scope_accepts_an_array_of_zone_models()
    {
        $zoneX = Zone::create(['name' => 'Zone X']);
        $zoneY = Zone::create(['name' => 'Zone Y']);
        $zoneZ = Zone::create(['name' => 'Zone Z']);
        TaxRate::create(['name' => 'TR5', 'zone_id' => $zoneZ->id, 'rate' => 5]);
        TaxRate::create(['name' => 'TR6', 'zone_id' => $zoneX->id, 'rate' => 5]);
        TaxRate::create(['name' => 'TR7', 'rate' => 5]);
        TaxRate::create(['name' => 'TR8', 'zone_id' => $zoneY->id, 'rate' => 5]);
        TaxRate::create(['name' => 'TR9', 'zone_id' => $zoneY->id, 'rate' => 5]);
        TaxRate::create(['name' => 'TR10', 'zone_id' => $zoneY->id, 'rate' => 5]);

        $methods = TaxRate::forZones([$zoneX, $zoneY])->get();
        $this->assertCount(4, $methods);
        $this->assertContains('TR6', $methods->pluck('name'));
        $this->assertContains('TR8', $methods->pluck('name'));
        $this->assertContains('TR9', $methods->pluck('name'));
        $this->assertContains('TR10', $methods->pluck('name'));
        $this->assertNotContains('TR5', $methods->pluck('name'));
        $this->assertNotContains('TR7', $methods->pluck('name'));
    }

    #[Test] public function the_for_zones_scope_accepts_a_collection_of_zone_models()
    {
        $zoneK = Zone::create(['name' => 'Zone K']);
        $zoneM = Zone::create(['name' => 'Zone M']);
        TaxRate::create(['name' => 'TR11', 'zone_id' => $zoneM->id, 'rate' => 12]);
        TaxRate::create(['name' => 'TR12', 'zone_id' => $zoneK->id, 'rate' => 12]);
        TaxRate::create(['name' => 'TR13', 'rate' => 12]);

        $methods = TaxRate::forZones(collect([$zoneK, $zoneM]))->get();
        $this->assertCount(2, $methods);
        $this->assertContains('TR11', $methods->pluck('name'));
        $this->assertContains('TR12', $methods->pluck('name'));
        $this->assertNotContains('TR13', $methods->pluck('name'));
    }
}
