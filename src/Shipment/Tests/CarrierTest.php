<?php

declare(strict_types=1);

/**
 * Contains the CarrierTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-28
 *
 */

namespace Vanilo\Shipment\Tests;

use Illuminate\Support\Str;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Shipment\Models\Carrier;

class CarrierTest extends TestCase
{
    #[Test] public function it_can_be_created_with_minimal_data()
    {
        $dhl = Carrier::create(['name' => 'DHL Germany']);

        $this->assertInstanceOf(Carrier::class, $dhl);
        $this->assertEquals('DHL Germany', $dhl->getName());
    }

    #[Test] public function the_name_field_is_accessible()
    {
        $dhl = Carrier::create(['name' => 'DHL Netherlands']);

        $this->assertEquals('DHL Netherlands', $dhl->name);
    }

    #[Test] public function the_name_method_returns_a_string_even_if_the_underlying_field_is_null()
    {
        $dhl = new Carrier();

        $this->assertEquals('', $dhl->getName());
    }

    #[Test] public function is_active_is_true_by_default()
    {
        $budbee = Carrier::create(['name' => 'Budbee'])->fresh();

        $this->assertTrue($budbee->is_active);
    }

    #[Test] public function can_be_marked_as_inactive()
    {
        $dpd = Carrier::create(['name' => 'Budbee', 'is_active' => false])->fresh();

        $this->assertFalse($dpd->is_active);
    }

    #[Test] public function active_and_inactive_entries_can_be_scoped()
    {
        for ($i = 0; $i < 3; $i++) {
            Carrier::create(['name' => Str::uuid(), 'is_active' => false]);
        }

        for ($i = 0; $i < 7; $i++) {
            Carrier::create(['name' => Str::uuid(), 'is_active' => true]);
        }

        $this->assertCount(10, Carrier::all());
        $this->assertCount(7, Carrier::actives()->get());
        $this->assertCount(3, Carrier::inactives()->get());
    }

    #[Test] public function the_configuration_is_an_empty_array_by_default()
    {
        $plPost = Carrier::create(['name' => 'Poczta Polska']);

        $this->assertIsArray($plPost->configuration);
        $this->assertEmpty($plPost->configuration);
    }

    #[Test] public function the_configuration_can_be_set_as_an_array()
    {
        $postNord = Carrier::create(['name' => 'Post Nord']);

        $postNord->configuration = ['some_key' => 'some value'];
        $postNord->save();
        $postNord = $postNord->fresh();

        $this->assertEquals('some value', $postNord->configuration['some_key']);
    }
}
