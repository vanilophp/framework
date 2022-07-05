<?php

declare(strict_types=1);

/**
 * Contains the OrderChannelTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-07-05
 *
 */

namespace Vanilo\Foundation\Tests;

use Vanilo\Channel\Models\Channel;
use Vanilo\Foundation\Models\Order;
use Vanilo\Order\Models\OrderStatus;

class OrderChannelTest extends TestCase
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
}
