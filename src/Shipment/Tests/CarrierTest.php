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

use Vanilo\Shipment\Models\Carrier;

class CarrierTest extends TestCase
{
    /** @test */
    public function it_can_be_created_with_minimal_data()
    {
        $dhl = Carrier::create(['name' => 'DHL Germany']);

        $this->assertInstanceOf(Carrier::class, $dhl);
        $this->assertEquals('DHL Germany', $dhl->name());
    }

    /** @test */
    public function is_active_is_true_by_default()
    {
        $budbee = Carrier::create(['name' => 'Budbee'])->fresh();

        $this->assertTrue($budbee->is_active);
    }

    /** @test */
    public function can_be_marked_as_inactive()
    {
        $dpd = Carrier::create(['name' => 'Budbee', 'is_active' => false])->fresh();

        $this->assertFalse($dpd->is_active);
    }

    /** @test */
    public function the_configuration_is_an_empty_array_by_default()
    {
        $plPost = Carrier::create(['name' => 'Poczta Polska']);

        $this->assertIsArray($plPost->configuration);
        $this->assertEmpty($plPost->configuration);
    }

    /** @test */
    public function the_configuration_can_be_set_as_an_array()
    {
        $postNord = Carrier::create(['name' => 'Post Nord']);

        $postNord->configuration = ['some_key' => 'some value'];
        $postNord->save();
        $postNord = $postNord->fresh();

        $this->assertEquals('some value', $postNord->configuration['some_key']);
    }
}
