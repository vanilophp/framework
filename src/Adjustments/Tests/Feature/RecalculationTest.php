<?php

declare(strict_types=1);

/**
 * Contains the RecalculationTest class.
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

class RecalculationTest extends TestCase
{
    /** @test */
    public function it_changes_the_value_on_recalculation_if_parameters_change()
    {
        $order = Order::create(['items_total' => 15]);
        $order->adjustments()->create(new SimpleShippingFee(3, 20));

        $this->assertEquals(3, $order->adjustments()->total());
        $order->items_total = 25;
        $order->recalculateAdjustments();

        $this->assertEquals(0, $order->adjustments()->total());
    }
}
