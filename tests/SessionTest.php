<?php
/**
 * Contains the Session test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-29
 *
 */


namespace Vanilo\Cart\Tests;

use Vanilo\Cart\Facades\Cart;

/**
 * @test
 */
class SessionTest extends TestCase
{
    /**
     * @test
     */
    public function a_session_has_no_cart_by_default()
    {
        $this->assertFalse(Cart::exists());
    }
}
