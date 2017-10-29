<?php
/**
 * Contains the CartFacade Test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */


namespace Vanilo\Cart\Tests;


use Vanilo\Cart\Facades\Cart;

class CartFacadeTest extends TestCase
{
    /**
     * @test
     */
    public function the_cart_facade_returns_a_nonexistent_cart_by_default()
    {
        $this->assertFalse(Cart::exists());
        $this->assertNull(Cart::model());
    }

}