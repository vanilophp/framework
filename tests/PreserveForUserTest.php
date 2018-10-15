<?php
/**
 * Contains the PreserveForUserTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-15
 *
 */

namespace Vanilo\Cart\Tests;

class PreserveForUserTest extends TestCase
{
    /** @test */
    public function it_preserves_the_cart_for_the_user_after_logout_if_feature_is_enabled()
    {
        // have logged in user
        // create cart
        // logout
        // test if cart is still present in the db
    }

    public function it_restores_the_cart_if_user_logs_back_int_and_feature_is_enabled()
    {
        // have logged in user
        // create cart
        // logout
        // Check if cart is empty (?) || destroy session
        // log back in
        // see the restored cart
    }

    public function it_does_not_restore_the_saved_cart_if_there_is_another_cart_for_the_session()
    {
        // have logged in user
        // create cart (A)
        // logout
        // Create another cart (B)
        // log back in
        // see we still have cart B and cart A was not restored
    }
}
