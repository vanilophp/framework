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

use PHPUnit\Framework\Error\Notice;
use Vanilo\Cart\Facades\Cart;

class SessionTest extends TestCase
{
    /** @test */
    public function a_session_has_no_cart_by_default()
    {
        $this->assertFalse(Cart::exists());
    }

    /** @test */
    public function cart_manager_emits_a_notice_if_the_session_key_config_entry_is_empty()
    {
        config(['vanilo.cart.session_key' => null]);

        $this->expectException(Notice::class);
        //$this->expectExceptionMessageRegExp('/^Vanilo cart session key is empty.*/');

        $this->assertFalse(Cart::exists());
    }
}
