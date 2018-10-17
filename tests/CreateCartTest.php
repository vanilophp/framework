<?php
/**
 * Contains the CreateCartTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */

namespace Vanilo\Cart\Tests;

use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;

class CreateCartTest extends TestCase
{
    /**
     * @test
     */
    public function a_cart_gets_created_if_you_add_an_item_to_it()
    {
        $this->assertTrue(Cart::doesNotExist());
        $product = Product::create([
            'name'  => 'Tusnad Mineral Water 0.5',
            'price' => 1.25
        ]);

        Cart::addItem($product);

        $this->assertTrue(Cart::exists());
    }

    /**
     * @test
     */
    public function item_count_returns_the_number_of_items_in_the_cart()
    {
        $product = Product::create([
            'name'  => 'S8 Mineral Water 0.5',
            'price' => 1.35
        ]);

        Cart::addItem($product);

        $this->assertEquals(1, Cart::itemCount());

        Cart::addItem($product);

        $this->assertEquals(2, Cart::itemCount());
    }

    /**
     * @test
     */
    public function number_of_items_to_add_can_be_specified()
    {
        $product = Product::create([
            'name'  => 'V8 Mineral Water 2L',
            'price' => 1.95
        ]);

        Cart::addItem($product, 8);

        $this->assertEquals(8, Cart::itemCount());

        Cart::addItem($product, 2);

        $this->assertEquals(10, Cart::itemCount());
    }
}
