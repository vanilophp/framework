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
}
