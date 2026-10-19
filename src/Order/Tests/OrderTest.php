<?php

declare(strict_types=1);

/**
 * Contains the OrderTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-29
 *
 */

namespace Vanilo\Order\Tests;

use Illuminate\Database\UniqueConstraintViolationException;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Models\Order;

class OrderTest extends TestCase
{
    #[Test] public function it_can_be_instantiated_and_it_implements_the_interface()
    {
        $order = new Order();

        $this->assertInstanceOf(OrderContract::class, $order);
    }

    #[Test] public function it_finds_orders_by_number()
    {
        $order = Order::create([
            'number' => 'PO-77651231'
        ]);

        $foundOrder = Order::findByNumber('PO-77651231');

        $this->assertGreaterThan(0, $order->id);
        $this->assertEquals($order->id, $foundOrder->id);
        $this->assertEquals('PO-77651231', $foundOrder->number);
    }

    #[Test] public function find_by_number_returns_null_for_nonexisting_orders()
    {
        $this->assertNull(Order::findByNumber('HEY, no such order'));
    }

    #[Test] public function the_cart_id_is_optional()
    {
        $order = Order::create(['number' => 'NULL_CART_ID_1234']);

        $this->assertNull($order->fresh()->cart_id);
    }

    #[Test] public function the_cart_id_can_be_set()
    {
        $order = Order::create(['number' => 'WITH_CART_ID_1234', 'cart_id' => 1234]);

        $this->assertEquals(1234, $order->fresh()->cart_id);
    }

    #[Test] public function two_orders_can_not_have_the_same_cart_id()
    {
        $order1 = Order::create(['number' => 'WITH_CART_ID_1500_01', 'cart_id' => 1500]);
        $this->assertEquals(1500, $order1->fresh()->cart_id);

        $this->expectException(UniqueConstraintViolationException::class);
        Order::create(['number' => 'WITH_CART_ID_1500_02', 'cart_id' => 1500]);
    }
}
