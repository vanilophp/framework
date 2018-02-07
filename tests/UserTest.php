<?php
/**
 * Contains the UserTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-02-03
 *
 */


namespace Vanilo\Cart\Tests;


use Vanilo\Cart\Facades\Cart;

class UserTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_no_user_when_not_logged_in()
    {
        $this->assertNull(Cart::getUser());
    }

    /**
     * @test
     */
    public function user_can_be_set_manually()
    {
        $this->assertNull(Cart::getUser());
        //$user = new \App\User();

        //Cart::setUser($user);
    }

}
