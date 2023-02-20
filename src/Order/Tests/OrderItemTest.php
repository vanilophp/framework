<?php

declare(strict_types=1);

/**
 * Contains the OrderItemTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */

namespace Vanilo\Order\Tests;

use Illuminate\Support\Str;
use Konekt\Enum\Enum;
use Vanilo\Contracts\Buyable;
use Vanilo\Order\Contracts\FulfillmentStatus as FulfillmentStatusContract;
use Vanilo\Order\Models\FulfillmentStatus;
use Vanilo\Order\Models\Order;
use Vanilo\Order\Tests\Dummies\Product;

class OrderItemTest extends TestCase
{
    /** @var Product */
    protected $theMoonRing;

    public function setUp(): void
    {
        parent::setUp();

        $this->theMoonRing = Product::create([
            'name' => 'The Moon Ring',
            'sku' => 'B01KR3SAIS',
            'price' => 17.95
        ]);
    }

    /** @test */
    public function product_can_be_added_to_order_item()
    {
        $order = Order::create([
            'number' => 'FVJH8'
        ]);

        $order->items()->create([
            'product_type' => 'product',
            'product_id' => $this->theMoonRing->getId(),
            'quantity' => 1,
            'name' => $this->theMoonRing->getName(),
            'price' => $this->theMoonRing->getPrice()
        ]);

        $item = $order->items->first();
        $product = $item->product;
        $this->assertInstanceOf(Buyable::class, $product);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($this->theMoonRing->getName(), $product->getName());

        // Re-fetch from DB
        $order = $order->fresh();

        // And repeat the test
        $item = $order->items->first();
        $product = $item->product;
        $this->assertInstanceOf(Buyable::class, $product);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($this->theMoonRing->getName(), $product->getName());
    }

    /** @test */
    public function order_items_can_be_configured()
    {
        $order = Order::create([
            'number' => 'WYHP7'
        ]);

        $order->items()->create([
            'product_type' => 'product',
            'product_id' => $this->theMoonRing->getId(),
            'quantity' => 1,
            'configuration' => ['hello' => 'dolly buster'],
            'name' => $this->theMoonRing->getName(),
            'price' => $this->theMoonRing->getPrice()
        ]);

        $item = $order->items->first();

        $this->assertEquals('dolly buster', $item->configuration['hello']);
    }

    /** @test */
    public function an_order_item_has_a_default_fullfillment_state()
    {
        $order = Order::create(['number' => Str::uuid()->getHex()->toString()]);

        $order->items()->create([
            'product_type' => 'product',
            'product_id' => $this->theMoonRing->getId(),
            'quantity' => 1,
            'name' => $this->theMoonRing->getName(),
            'price' => $this->theMoonRing->getPrice()
        ]);

        $item = $order->items->first();

        $this->assertInstanceOf(Enum::class, $item->fulfillment_status);
        $this->assertInstanceOf(FulfillmentStatusContract::class, $item->fulfillment_status);
        $this->assertEquals(FulfillmentStatus::defaultValue(), $item->fulfillment_status->value());
    }
}
