<?php
/**
 * Contains the NonexistentCart Test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */


namespace Vanilo\Cart\Tests;

use Vanilo\Cart\Facades\Cart;

class NonexistentCartTest extends TestCase
{
    /**
     * @test
     */
    public function the_cart_facade_returns_a_nonexistent_cart_by_default()
    {
        $this->assertTrue(Cart::doesNotExist());
        $this->assertNull(Cart::model());
    }

    /**
     * @test
     */
    public function item_count_is_zero_for_nonexistent_carts()
    {
        $this->assertEquals(0, Cart::itemCount());
    }

    /**
     * @test
     */
    public function cart_reports_is_empty_for_nonexistent_carts()
    {
        $this->assertTrue(Cart::isEmpty());
    }
}
