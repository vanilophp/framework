<?php

declare(strict_types=1);

namespace Feature;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Adjustments\Adjusters\SimpleTax;
use Vanilo\Adjustments\Adjusters\SimpleTaxDeduction;
use Vanilo\Adjustments\Tests\Examples\Order;
use Vanilo\Adjustments\Tests\TestCase;

class SimpleTaxTest extends TestCase
{
    #[Test] public function a_simple_non_included_tax_can_be_added_to_an_adjustable_order()
    {
        $order = Order::create(['items_total' => 100]);
        $order->adjustments()->create(new SimpleTax(20, false));

        $this->assertCount(1, $order->adjustments());
        $this->assertEquals(20, $order->adjustments()->total());
        $this->assertEquals(120, $order->total());
    }

    #[Test] public function an_included_tax_gets_calculated_correctly_based_on_the_rate_and_order_items_total()
    {
        $order = Order::create(['items_total' => 119]);
        $order->adjustments()->create(new SimpleTax(19, true));

        $this->assertEquals(119, $order->total());
        $this->assertTrue($order->adjustments()->first()->isCharge());
        $this->assertCount(1, $order->adjustments());
        $this->assertEquals(19, $order->adjustments()->first()->getAmount());
    }

    #[Test] public function a_simple_non_included_tax_deductaion_can_be_added_to_an_adjustable_order()
    {
        $order = Order::create(['items_total' => 100]);
        $order->adjustments()->create(new SimpleTaxDeduction(20, false));

        $this->assertCount(1, $order->adjustments());
        $this->assertEquals(-20, $order->adjustments()->total());
        $this->assertEquals(80, $order->total());
    }

    #[Test] public function an_included_tax_deducation_gets_calculated_correctly_based_on_the_rate_and_order_items_total()
    {
        $order = Order::create(['items_total' => 119]);
        $order->adjustments()->create(new SimpleTaxDeduction(19, true));

        $this->assertEquals(119, $order->total());
        $this->assertTrue($order->adjustments()->first()->isCredit());
        $this->assertCount(1, $order->adjustments());
        $this->assertEquals(-19, $order->adjustments()->first()->getAmount());
    }
}