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

use Illuminate\Support\Facades\Session;
use Vanilo\Cart\CartManager;
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

    /** @test */
    public function cart_id_in_session_without_db_entry_returns_proper_empty_values()
    {
        $sessionKey = config(CartManager::CONFIG_SESSION_KEY);
        session([$sessionKey => 999888777]);

        $this->assertNull(Cart::getUser());
        $this->assertEquals(0, Cart::itemCount());
        $this->assertEmpty(Cart::getItems());
        $this->assertEquals(0, Cart::total());
        $this->assertFalse(Cart::exists());
        $this->assertTrue(Cart::doesNotExist());
    }
}
