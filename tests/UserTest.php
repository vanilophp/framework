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
use Vanilo\Cart\Tests\Dummies\User;

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
        $user = User::create([
            'email'    => 'ever@green.me',
            'name'     => 'Molly Green',
            'password' => bcrypt('brute force')
        ])->fresh();

        Cart::setUser($user);

        $this->assertEquals($user->id, Cart::getUser()->id);
    }

    /**
     * @test
     */
    public function user_gets_automatically_assigned_when_authenticated()
    {
        $user = User::create([
            'email'    => 'ever@green.me',
            'name'     => 'Molly Green',
            'password' => bcrypt('brute force')
        ])->fresh();

        $this->be($user);

        $this->assertAuthenticatedAs($user);

        $this->assertInstanceOf(User::class, Cart::getUser());
        $this->assertEquals($user->id, Cart::getUser()->id);
    }

}
