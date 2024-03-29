<?php

declare(strict_types=1);

/**
 * Contains the OrderExtensionsTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-07-05
 *
 */

namespace Vanilo\Foundation\Tests;

use Illuminate\Support\Str;
use Vanilo\Channel\Models\Channel;
use Vanilo\Foundation\Models\Customer;
use Vanilo\Foundation\Models\Order;
use Vanilo\Order\Models\OrderStatus;
use Vanilo\Shipment\Models\ShippingMethod;

class OrderExtensionsTest extends TestCase
{
    /** @test */
    public function an_order_can_be_assigned_to_a_channel()
    {
        $channel = Channel::create(['name' => 'Web Belgie']);
        $order = Order::create([
            'number' => 'BON-1RT',
            'status' => OrderStatus::defaultValue(),
            'channel_id' => $channel->id,
        ]);

        $this->assertEquals($channel->id, $order->channel_id);
        $this->assertInstanceOf(Channel::class, $order->channel);
        $this->assertEquals('Web Belgie', $order->channel->name);
    }

    /** @test */
    public function an_order_can_be_assigned_to_a_customer()
    {
        $customer = Customer::create(['name' => 'Nimfas Corporation']);
        $order = Order::create([
            'number' => Str::uuid()->getHex()->toString(),
            'customer_id' => $customer->id,
        ]);

        $this->assertInstanceOf(Customer::class, $order->customer);
        $this->assertEquals($customer->id, $order->customer->id);
    }

    /** @test */
    public function a_shipping_method_can_be_assigned_to_an_order()
    {
        $shippingMethod = ShippingMethod::create(['name' => 'Awesome Drops']);
        $order = Order::create([
            'number' => Str::uuid()->getHex()->toString(),
            'shipping_method_id' => $shippingMethod->id,
        ]);

        $this->assertInstanceOf(ShippingMethod::class, $order->shippingMethod);
        $this->assertEquals($shippingMethod->id, $order->shippingMethod->id);
    }
}
