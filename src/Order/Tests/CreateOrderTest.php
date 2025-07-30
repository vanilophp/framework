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

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Konekt\Address\Models\Address;
use Konekt\Address\Models\AddressType;
use Konekt\Address\Models\Country;
use Konekt\Enum\Enum;
use Konekt\User\Models\User;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Order\Contracts\FulfillmentStatus as FulfillmentStatusContract;
use Vanilo\Order\Contracts\Order as OrderContract;
use Vanilo\Order\Contracts\OrderStatus as OrderStatusContract;
use Vanilo\Order\Models\Billpayer;
use Vanilo\Order\Models\FulfillmentStatus;
use Vanilo\Order\Models\Order;
use Vanilo\Order\Models\OrderStatus;
use Vanilo\Order\Tests\Factories\AddressFactory;
use Vanilo\Order\Tests\Factories\BillpayerFactory;
use Vanilo\Order\Tests\Factories\UserFactory;

class CreateOrderTest extends TestCase
{
    #[Test] public function order_can_be_created_with_minimal_data()
    {
        $order = Order::create([
            'number' => 'PO123456',
            'status' => OrderStatus::defaultValue()
        ]);

        $this->assertInstanceOf(OrderContract::class, $order);
    }

    #[Test] public function order_cant_be_created_without_order_number()
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

    #[Test] public function order_number_is_unique()
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

    #[Test] public function order_status_is_an_enum()
    {
        $order = Order::create([
            'number' => 'LMN6G1',
            'status' => OrderStatus::CANCELLED
        ]);

        $this->assertInstanceOf(Enum::class, $order->status);
        $this->assertInstanceOf(OrderStatusContract::class, $order->status);
    }

    #[Test] public function order_has_default_status_if_none_was_given()
    {
        $order = Order::create([
            'number' => 'XK012W44'
        ]);

        $this->assertEquals(OrderStatus::defaultValue(), $order->status->value());
    }

    #[Test] public function order_status_can_be_set()
    {
        $order = Order::create([
            'number' => 'YHSIE',
            'status' => OrderStatus::CANCELLED
        ]);

        $this->assertEquals(OrderStatus::CANCELLED, $order->status->value());
    }

    #[Test] public function the_fulfillment_status_is_an_enum()
    {
        $order = Order::create([
            'number' => 'LMN6G1',
            'fulfillment_status' => FulfillmentStatus::AWAITING_SHIPMENT
        ]);

        $this->assertInstanceOf(Enum::class, $order->fulfillment_status);
        $this->assertInstanceOf(FulfillmentStatusContract::class, $order->fulfillment_status);
    }

    #[Test] public function it_has_a_default_fulfillment_status_if_none_was_given()
    {
        $order = Order::create([
            'number' => 'WK012X44'
        ]);

        $this->assertEquals(FulfillmentStatus::defaultValue(), $order->fulfillment_status->value());
    }

    #[Test] public function the_fulfillment_status_can_be_set()
    {
        $order = Order::create([
            'number' => 'PHSFE',
            'fulfillment_status' => FulfillmentStatus::PARTIALLY_FULFILLED,
        ]);

        $this->assertEquals(FulfillmentStatus::PARTIALLY_FULFILLED, $order->fulfillment_status->value());
    }

    #[Test] public function the_language_can_be_set()
    {
        $order = Order::create([
            'number' => 'YAHRYHANI',
            'language' => 'fa',
        ]);

        $this->assertEquals('fa', $order->language);
    }

    #[Test] public function the_ordered_at_can_be_set_and_is_a_datetime()
    {
        $order = Order::create([
            'number' => 'USKH2',
            'ordered_at' => '2022-12-30T09:00:00'
        ]);

        $this->assertInstanceOf(Carbon::class, $order->ordered_at);
        $this->assertEquals('2022-12-30T09:00:00', $order->ordered_at->format('Y-m-d\TH:i:s'));
    }

    #[Test] public function it_sets_the_ordered_at_date_to_be_the_same_as_the_created_at_if_no_explicit_ordered_at_date_is_passed()
    {
        $order = Order::create([
            'number' => 'DDKX$Z'
        ]);

        $this->assertEquals(
            $order->created_at->toIso8601ZuluString(),
            $order->ordered_at->toIso8601ZuluString()
        );
    }

    #[Test] public function all_fields_can_be_properly_set()
    {
        Country::create([
            'id' => 'DE',
            'name' => 'Germany',
            'phonecode' => 49,
            'is_eu_member' => 1
        ]);

        UserFactory::new()->createMany(4);
        AddressFactory::new()->createMany(
            [
                ['type' => AddressType::SHIPPING],
                ['type' => AddressType::SHIPPING],
                ['type' => AddressType::SHIPPING],
                ['type' => AddressType::SHIPPING],
                ['type' => AddressType::SHIPPING],
            ],
        );
        $user = User::orderBy('id', 'desc')->first();
        $shippingAddress = Address::orderBy('id', 'desc')->first();
        $billpayer = BillpayerFactory::new()->create();

        $order = Order::create([
            'number' => 'UEOIP',
            'status' => OrderStatus::COMPLETED(),
            'fulfillment_status' => FulfillmentStatus::FULFILLED(),
            'language' => 'de',
            'currency' => 'EUR',
            'ordered_at' => '2023-01-15 11:35:27',
            'user_id' => $user->id,
            'billpayer_id' => $billpayer->id,
            'shipping_address_id' => $shippingAddress->id,
            'notes' => 'Never fight an inanimate object'
        ]);

        $this->assertEquals('UEOIP', $order->number);
        $this->assertEquals('UEOIP', $order->getNumber());

        $this->assertTrue($order->status->isCompleted());
        $this->assertTrue($order->fulfillment_status->isFulfilled());

        $this->assertEquals('de', $order->language);
        $this->assertEquals('EUR', $order->currency);
        $this->assertEquals('2023-01-15T11:35:27Z', $order->ordered_at->toIso8601ZuluString());
        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($billpayer->id, $order->billpayer_id);
        $this->assertEquals($shippingAddress->id, $order->shipping_address_id);

        $this->assertEquals('Never fight an inanimate object', $order->notes);

        // Let's see if it actually persists
        $order = $order->fresh();

        $this->assertEquals('UEOIP', $order->number);
        $this->assertEquals('UEOIP', $order->getNumber());

        $this->assertTrue($order->status->isCompleted());
        $this->assertTrue($order->fulfillment_status->isFulfilled());

        $this->assertEquals($user->id, $order->user_id);
        $this->assertEquals($billpayer->id, $order->billpayer_id);
        $this->assertEquals($shippingAddress->id, $order->shipping_address_id);

        $this->assertEquals('Never fight an inanimate object', $order->notes);
    }
}
