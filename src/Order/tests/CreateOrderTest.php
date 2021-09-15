<?php

declare(strict_types=1);
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

use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Konekt\Address\Models\Address;
use Konekt\Address\Models\AddressType;
use Konekt\Address\Models\Country;
use Konekt\Enum\Enum;
use Konekt\User\Models\User;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderStatus as OrderStatusContract;
use Vanilo\Order\Models\Billpayer;
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
            'status' => OrderStatus::defaultValue()
        ]);

        $this->assertInstanceOf(OrderContract::class, $order);
    }

    /**
     * @test
     */
    public function order_cant_be_created_without_order_number()
    {
        $this->expectException(\PDOException::class);

        if ('mysql' == DB::connection()->getPDO()->getAttribute(\PDO::ATTR_DRIVER_NAME)) {
            DB::connection()->statement('SET sql_mode = \'STRICT_TRANS_TABLES\'');
            $this->expectExceptionMessageMatches("/'number' doesn't have a default/i");
        } else {
            $this->expectExceptionMessageMatches('/NOT NULL/i');
        }

        Order::create([
            'status' => OrderStatus::defaultValue()
        ]);
    }

    /** @test */
    public function order_number_is_unique()
    {
        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches('/UNIQUE/i');

        Order::create([
            'number' => 'DUPLICATE'
        ]);
        Order::create([
            'number' => 'DUPLICATE'
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
        Country::create([
            'id' => 'DE',
            'name' => 'Germany',
            'phonecode' => 49,
            'is_eu_member' => 1
        ]);

        factory(User::class, 271)->create();
        factory(Address::class, 8)->create(['type' => AddressType::SHIPPING]);
        $user = User::orderBy('id', 'desc')->first();
        $shippingAddress = Address::orderBy('id', 'desc')->first();
        $billpayer = factory(Billpayer::class)->create();

        $order = Order::create([
            'number' => 'UEOIP',
            'status' => OrderStatus::COMPLETED(),
            'user_id' => $user->id,
            'billpayer_id' => $billpayer->id,
            'shipping_address_id' => $shippingAddress->id,
            'notes' => 'Never fight an inanimate object'
        ]);

        $this->assertEquals('UEOIP', $order->number);
        $this->assertEquals('UEOIP', $order->getNumber());

        $this->assertTrue($order->status->isCompleted());

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($billpayer->id, $order->billpayer_id);
        $this->assertEquals($shippingAddress->id, $order->shipping_address_id);

        $this->assertEquals('Never fight an inanimate object', $order->notes);

        // Let's see if it actually persists
        $order = $order->fresh();

        $this->assertEquals('UEOIP', $order->number);
        $this->assertEquals('UEOIP', $order->getNumber());

        $this->assertTrue($order->status->isCompleted());

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($billpayer->id, $order->billpayer_id);
        $this->assertEquals($shippingAddress->id, $order->shipping_address_id);

        $this->assertEquals('Never fight an inanimate object', $order->notes);
    }
}
