<?php

declare(strict_types=1);

/**
 * Contains the CartItemsAdd Test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-30
 *
 */

namespace Vanilo\Cart\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Cart\Contracts\CartItem;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;

class CartItemsAddTest extends TestCase
{
    protected Product $product1;

    protected Product $product2;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product1 = Product::create([
            'name' => 'Random Product',
            'price' => 178
        ]);
        $this->product2 = Product::create([
            'name' => 'Another Product',
            'price' => 87
        ]);
    }

    #[Test] public function an_item_can_be_added_to_the_cart()
    {
        Cart::addItem($this->product1);

        $this->assertEquals(1, Cart::itemCount());
        $this->assertCount(1, Cart::model()->getItems());
        $this->assertEquals($this->product1->id, Cart::model()->getItems()->first()->product_id);
    }

    #[Test] public function adding_an_item_results_a_non_empty_cart()
    {
        Cart::addItem($this->product1);

        $this->assertTrue(Cart::isNotEmpty());
    }

    #[Test] public function multiple_items_can_be_added_to_the_cart()
    {
        Cart::addItem($this->product1);

        $this->assertEquals(1, Cart::itemCount());
        $this->assertCount(1, Cart::model()->getItems());

        Cart::addItem($this->product2);

        $this->assertEquals(2, Cart::itemCount());
        $this->assertCount(2, Cart::model()->getItems());

        $expectedProductIds = [$this->product1->id, $this->product2->id];
        $this->assertEquals(
            $expectedProductIds,
            Cart::model()->getItems()->pluck('product_id')->all()
        );
    }

    #[Test] public function adding_the_same_item_twice_doesnt_duplicate_lines_but_increases_quantity()
    {
        Cart::addItem($this->product1);

        $this->assertEquals(1, Cart::itemCount());
        $this->assertCount(1, Cart::model()->getItems());

        Cart::addItem($this->product1);

        $this->assertEquals(2, Cart::itemCount());
        $this->assertCount(1, Cart::model()->getItems());
    }

    #[Test] public function quantity_can_be_specified_when_adding_an_item()
    {
        Cart::addItem($this->product1, 3);

        $this->assertEquals(3, Cart::itemCount());

        Cart::addItem($this->product1, 2);

        $this->assertEquals(5, Cart::itemCount());
        $this->assertCount(1, Cart::model()->getItems());
    }

    #[Test] public function adding_an_item_returns_the_item_object()
    {
        $this->assertInstanceOf(
            CartItem::class,
            Cart::addItem($this->product2)
        );
    }

    #[Test] public function adding_a_product_twice_also_returns_the_item_object()
    {
        Cart::addItem($this->product1);

        $this->assertInstanceOf(
            CartItem::class,
            Cart::addItem($this->product1)
        );
    }

    #[Test] public function it_can_be_forced_to_create_a_new_item_when_adding_a_product_twice()
    {
        $item1 = Cart::addItem($this->product1);
        $item2 = Cart::addItem($this->product1, forceNewItem: true);

        $this->assertInstanceOf(CartItem::class, $item1);
        $this->assertInstanceOf(CartItem::class, $item2);
        $this->assertNotSame($item1, $item2);
        $this->assertNotEquals($item1->id, $item2->id);
        $this->assertEquals(2, Cart::itemCount());
        $this->assertCount(2, Cart::model()->getItems());
    }

    #[Test] public function cart_items_can_be_assigned_a_configuration_array()
    {
        $item = Cart::addItem($this->product1, 1, ['attributes' => ['configuration' => ['custom_message' => 'Hey Hello']]]);

        $this->assertEquals('Hey Hello', $item->configuration['custom_message']);
    }
}
