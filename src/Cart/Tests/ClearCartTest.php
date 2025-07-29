<?php

declare(strict_types=1);

/**
 * Contains the ClearCart Test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-30
 *
 */

namespace Vanilo\Cart\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Models\CartItemProxy;
use Vanilo\Cart\Tests\Dummies\Product;

class ClearCartTest extends TestCase
{
    protected Product $product5;

    protected Product $product6;

    protected function setUp(): void
    {
        parent::setUp();

        $this->product5 = Product::create([
            'name' => 'Spicy Ketchup',
            'price' => 1.29
        ]);

        $this->product6 = Product::create([
            'name' => 'Curry Sauce',
            'price' => 1.09
        ]);
    }

    #[Test] public function clearing_items_results_in_an_empty_cart()
    {
        Cart::addItem($this->product5);

        $this->assertEquals(1, Cart::itemCount());
        $this->assertCount(1, Cart::model()->getItems());

        Cart::clear();

        $this->assertEquals(0, Cart::itemCount());
        $this->assertCount(0, Cart::model()->getItems());
        $this->assertTrue(Cart::isEmpty());
    }

    #[Test] public function and_yes_of_course_multiple_items_get_all_deleted()
    {
        Cart::addItem($this->product5, 3);
        Cart::addItem($this->product6, 4);

        $this->assertEquals(7, Cart::itemCount());
        $this->assertCount(2, Cart::model()->getItems());

        Cart::clear();

        $this->assertEquals(0, Cart::itemCount());
        $this->assertCount(0, Cart::model()->getItems());
        $this->assertTrue(Cart::isEmpty());
    }

    #[Test] public function clear_cart_removes_items_from_the_db()
    {
        Cart::addItem($this->product5);
        Cart::addItem($this->product6);

        $cartId = Cart::model()->id;
        $this->assertCount(2, CartItemProxy::ofCart($cartId)->get());

        Cart::clear();

        $this->assertCount(0, CartItemProxy::ofCart($cartId)->get());
    }
}
