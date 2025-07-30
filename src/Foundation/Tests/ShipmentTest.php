<?php

declare(strict_types=1);

/**
 * Contains the ShipmentTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-02-20
 *
 */

namespace Vanilo\Foundation\Tests;

use Illuminate\Support\Str;
use Konekt\Address\Seeds\Countries;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Foundation\Models\Address;
use Vanilo\Foundation\Models\Order;
use Vanilo\Foundation\Models\OrderItem;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Models\Shipment;
use Vanilo\Foundation\Tests\Factories\AddressFactory;
use Vanilo\Order\Models\OrderStatus;

class ShipmentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('db:seed', ['--class' => Countries::class]);
    }

    #[Test] public function a_shipment_can_be_added_to_an_order()
    {
        $address = AddressFactory::new()->create(['country_id' => 'CA']);
        $order = Order::create([
            'number' => Str::uuid()->getHex()->toString(),
            'status' => OrderStatus::defaultValue(),
            'shipping_address_id' => $address->id,
        ]);

        $shipment = Shipment::create(['address_id' => $address->id]);
        $order->addShipment($shipment);

        $this->assertCount(1, $order->shipments);
        $this->assertEquals($shipment->id, $order->shipments->first()->id);
    }

    #[Test] public function multiple_shipments_can_be_added_to_an_order()
    {
        $address = AddressFactory::new()->create(['country_id' => 'CA']);
        $order = Order::create([
            'shipping_address_id' => $address->id,
            'number' => Str::uuid()->getHex()->toString(),
        ]);
        $shipment1 = Shipment::create(['address_id' => $address->id]);
        $shipment2 = Shipment::create(['address_id' => $address->id]);

        $order->addShipments($shipment1, $shipment2);

        $this->assertCount(2, $order->shipments);
        $this->assertEquals($shipment1->id, $order->shipments->first()->id);
        $this->assertEquals($shipment2->id, $order->shipments->last()->id);
    }

    #[Test] public function one_shipment_can_belong_to_multiple_orders()
    {
        $address = AddressFactory::new()->create(['country_id' => 'CA']);
        $order1 = Order::create(['shipping_address_id' => $address->id, 'number' => Str::uuid()->getHex()->toString()]);
        $order2 = Order::create(['shipping_address_id' => $address->id, 'number' => Str::uuid()->getHex()->toString()]);
        $shipment = Shipment::create(['address_id' => $address->id]);

        $shipment->addOrder($order1);
        $shipment->addOrder($order2);

        $this->assertCount(2, $shipment->orders);
        $this->assertEquals($order1->id, $shipment->orders->first()->id);
        $this->assertEquals($order2->id, $shipment->orders->last()->id);
    }

    #[Test] public function a_shipment_can_be_added_to_an_order_item()
    {
        $product = Product::create([
            'name' => 'Test Product',
            'sku' => Str::uuid()->getHex()->toString(),
            'price' => 17.95
        ]);
        $address = AddressFactory::new()->create(['country_id' => 'CA']);
        $order = Order::create([
            'number' => Str::uuid()->getHex()->toString(),
            'status' => OrderStatus::defaultValue(),
            'shipping_address_id' => $address->id,
        ]);
        /** @var OrderItem $theItem */
        $theItem = $order->items()->create([
            'product_type' => 'product',
            'product_id' => $product->id,
            'name' => 'Test',
            'price' => 17.95,
            'quantity' => 1,
        ]);

        $shipment = Shipment::create(['address_id' => $address->id]);
        $theItem->addShipment($shipment);

        $this->assertCount(1, $theItem->shipments);
        $this->assertEquals($shipment->id, $theItem->shipments->first()->id);
    }

    #[Test] public function one_shipment_can_contain_multiple_order_items()
    {
        $product1 = Product::create(['name' => 'Product 1', 'sku' => Str::uuid()->getHex()->toString(), 'price' => 17.95]);
        $product2 = Product::create(['name' => 'Product 2', 'sku' => Str::uuid()->getHex()->toString(), 'price' => 17.95]);
        $address = AddressFactory::new()->create(['country_id' => 'CA']);
        $order = Order::create(['shipping_address_id' => $address->id, 'number' => Str::uuid()->getHex()->toString()]);
        $item1 = $order->items()->create(['product_type' => 'product', 'product_id' => $product1->id, 'name' => 'Product 1', 'price' => 17.95, 'quantity' => 1]);
        $item2 = $order->items()->create(['product_type' => 'product', 'product_id' => $product2->id, 'name' => 'Product 2', 'price' => 17.95, 'quantity' => 1]);
        $shipment = Shipment::create(['address_id' => $address->id]);

        $shipment->addOrderItem($item1);
        $shipment->addOrderItem($item2);

        $this->assertCount(2, $shipment->orderItems);
        $this->assertEquals($item1->id, $shipment->orderItems->first()->id);
        $this->assertEquals($item2->id, $shipment->orderItems->last()->id);
    }

    #[Test] public function multiple_order_items_can_be_assigned_to_a_shipment_in_one_step()
    {
        $product3 = Product::create(['name' => 'Product 3', 'sku' => Str::uuid()->getHex()->toString(), 'price' => 9.99]);
        $product4 = Product::create(['name' => 'Product 4', 'sku' => Str::uuid()->getHex()->toString(), 'price' => 25]);
        $address = AddressFactory::new()->create(['country_id' => 'NL']);
        $order = Order::create(['shipping_address_id' => $address->id, 'number' => Str::uuid()->getHex()->toString()]);
        $item1 = $order->items()->create(['product_type' => 'product', 'product_id' => $product3->id, 'name' => 'Product 3', 'price' => 9.99, 'quantity' => 1]);
        $item2 = $order->items()->create(['product_type' => 'product', 'product_id' => $product4->id, 'name' => 'Product 4', 'price' => 25, 'quantity' => 1]);
        $shipment = Shipment::create(['address_id' => $address->id]);

        $shipment->addOrderItems($item1, $item2);

        $this->assertCount(2, $shipment->orderItems);
        $this->assertEquals($item1->id, $shipment->orderItems->first()->id);
        $this->assertEquals($item2->id, $shipment->orderItems->last()->id);
    }
}
