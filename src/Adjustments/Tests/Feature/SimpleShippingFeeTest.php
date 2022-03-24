<?php

declare(strict_types=1);

/**
 * Contains the SimpleShippingFeeTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-07-26
 *
 */

namespace Vanilo\Adjustments\Tests\Feature;

use Vanilo\Adjustments\Adjusters\SimpleShippingFee;
use Vanilo\Adjustments\Tests\Examples\Order;
use Vanilo\Adjustments\Tests\TestCase;

class SimpleShippingFeeTest extends TestCase
{
    /** @test */
    public function a_simple_shipping_fee_can_be_added_to_an_adjustable_order()
    {
        $order = Order::create(['items_total' => 15]);
        $order->adjustments()->create(new SimpleShippingFee(5, 49));

        $this->assertCount(1, $order->adjustments());
        $adjustment = $order->adjustments()->first();
        $this->assertInstanceOf(SimpleShippingFee::class, $adjustment->getAdjuster());
        $this->assertEquals(5, $adjustment->getData('amount'));
        $this->assertEquals(49, $adjustment->getData('freeThreshold'));
    }

    /** @test */
    public function its_value_is_the_configured_amount_if_the_order_items_total_is_lower_than_the_threshold()
    {
        $order = Order::create(['items_total' => 48.99]);
        $order->adjustments()->create(new SimpleShippingFee(5, 49));

        $this->assertEquals(5, $order->adjustments()->total());
    }

    /** @test */
    public function its_value_is_zero_if_the_order_items_total_is_greater_than_the_threshold()
    {
        $order = Order::create(['items_total' => 50]);
        $order->adjustments()->create(new SimpleShippingFee(5, 49));

        $this->assertEquals(0, $order->adjustments()->total());
    }
}
