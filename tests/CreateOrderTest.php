<?php
/**
 * Contains the CreateOrderTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-28
 *
 */


namespace Vanilo\Order\Tests;


use Konekt\Enum\Enum;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderStatus as OrderStatusContract;
use Vanilo\Order\Models\Order;
use Vanilo\Order\Models\OrderStatus;

class CreateOrderTest extends TestCase
{
    /**
     * @test
     */
    public function order_can_be_created_with_minimal_data()
    {
        $order = Order::create([
            'number' => 'PO123456',
            'status' => OrderStatus::__default
        ]);

        $this->assertInstanceOf(OrderContract::class, $order);
    }

    /**
     * @test
     */
    public function order_cant_be_created_without_order_number()
    {
        $this->expectException(\PDOException::class);
        $this->expectExceptionMessage('NOT NULL constraint failed');

        Order::create([
            'status' => OrderStatus::__default
        ]);
    }

    /**
     * @test
     */
    public function order_status_is_an_enum()
    {
        $order = Order::create([
            'number' => 'LMN6G1',
            'status' => OrderStatus::CANCELLED
        ]);

        $this->assertInstanceOf(Enum::class, $order->status);
        $this->assertInstanceOf(OrderStatusContract::class, $order->status);
    }

    /**
     * @test
     */
    public function order_has_default_status_if_none_was_given()
    {
        $order = Order::create([
            'number' => 'XK012W44'
        ]);

        $this->assertEquals(OrderStatus::defaultValue(), $order->status->value());
    }

    /**
     * @test
     */
    public function order_status_can_be_set()
    {
        $order = Order::create([
            'number' => 'YHSIE',
            'status' => OrderStatus::CANCELLED
        ]);

        $this->assertEquals(OrderStatus::CANCELLED, $order->status->value());
    }

    /**
     * @test
     */
    public function all_fields_can_be_properly_set()
    {
        $order = Order::create([
            'number'              => 'UEOIP',
            'status'              => OrderStatus::COMPLETED(),
            'user_id'             => '271',
            'billpayer_id'        => '19072',
            'shipping_address_id' => '19073',
            'notes'               => 'Never fight an inanimate object'
        ]);

        $this->assertEquals('UEOIP', $order->number);
        $this->assertEquals('UEOIP', $order->getNumber());

        $this->assertTrue($order->status->isCompleted());

        $this->assertEquals(271, $order->user_id);
        $this->assertEquals(19072, $order->billpayer_id);
        $this->assertEquals(19073, $order->shipping_address_id);

        $this->assertEquals('Never fight an inanimate object', $order->notes);

        // Let's see if it actually persists
        $order = $order->fresh();

        $this->assertEquals('UEOIP', $order->number);
        $this->assertEquals('UEOIP', $order->getNumber());

        $this->assertTrue($order->status->isCompleted());

        $this->assertEquals(271, $order->user_id);
        $this->assertEquals(19072, $order->billpayer_id);
        $this->assertEquals(19073, $order->shipping_address_id);

        $this->assertEquals('Never fight an inanimate object', $order->notes);
    }
}
