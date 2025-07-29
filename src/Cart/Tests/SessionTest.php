<?php

declare(strict_types=1);

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

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Cart\Exceptions\InvalidCartConfigurationException;
use Vanilo\Cart\Facades\Cart;

class SessionTest extends TestCase
{
    #[Test] public function a_session_has_no_cart_by_default()
    {
        $this->assertFalse(Cart::exists());
    }

    #[Test] public function cart_manager_emits_a_notice_if_the_session_key_config_entry_is_empty()
    {
        config(['vanilo.cart.session_key' => null]);
        $this->expectException(InvalidCartConfigurationException::class);
        Cart::exists();
    }
}
