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

use Illuminate\Support\Facades\Auth;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;
use Vanilo\Cart\Tests\Dummies\User;

class UserTest extends TestCase
{
    /** @var User */
    protected $user;

    /**
     * @test
     */
    public function non_existing_cart_has_no_user()
    {
        $this->assertTrue(Cart::doesNotExist());
        $this->assertNull(Cart::getUser());
    }

    /**
     * @test
     */
    public function no_user_for_guest_sessions()
    {
        $this->assertGuest();

        Cart::create();
        $this->assertNull(Cart::getUser());
    }

    /**
     * @test
     */
    public function user_can_be_set_manually()
    {
        Cart::create();
        Cart::setUser($this->user);

        $this->assertEquals($this->user->id, Cart::getUser()->id);
    }

    /**
     * @test
     */
    public function gets_automatically_assigned_to_user_on_login()
    {
        $this->assertGuest();

        Cart::create();
        $this->assertNull(Cart::getUser());

        $this->be($this->user);

        $this->assertAuthenticatedAs($this->user);

        $this->assertInstanceOf(User::class, Cart::getUser());
        $this->assertEquals($this->user->id, Cart::getUser()->id);
    }

    /**
     * @test
     */
    public function new_carts_are_associated_to_already_logged_in_users()
    {
        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);

        Cart::create();

        $this->assertInstanceOf(User::class, Cart::getUser());
        $this->assertEquals($this->user->id, Cart::getUser()->id);
    }

    /**
     * @test
     */
    public function gets_automatically_dissociated_from_user_when_they_log_out()
    {
        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);

        Cart::create();
        $this->assertEquals($this->user->id, Cart::getUser()->id);

        Auth::logout();
        $this->assertGuest();

        $this->assertNull(Cart::getUser());
    }

    /**
     * @test
     */
    public function adding_a_product_to_a_nonexisting_cart_with_logged_in_user_results_in_a_cart_associated_to_the_authenticated_user()
    {
        $this->be($this->user);

        $this->assertTrue(Cart::doesNotExist());

        Cart::addItem(Product::create([
            'name'  => 'Rolls Royce',
            'price' => '100000'
        ]));

        $this->assertEquals($this->user->id, Cart::getUser()->id);
    }

    /**
     * @test
     */
    public function automatic_user_assignment_can_be_disabled_in_config()
    {
        config(['vanilo.cart.auto_assign_user' => false]);
        $this->be($this->user);

        Cart::addItem(Product::create([
            'name'  => 'Rolls Royce',
            'price' => '100000'
        ]));

        $this->assertNull(Cart::getUser());
    }

    /**
     * @test
     */
    public function if_automatic_user_assignment_is_disabled_user_does_not_get_associated_with_an_existing_cart_on_login()
    {
        config(['vanilo.cart.auto_assign_user' => false]);

        $this->assertGuest();

        Cart::addItem(Product::create([
            'name'  => 'Rolls Royce',
            'price' => '100000'
        ]));

        $this->be($this->user);

        $this->assertNull(Cart::getUser());
    }

    /**
     * @test
     */
    public function if_automatic_user_assignment_is_disabled_user_does_not_get_dissociated_from_an_existing_cart_on_logout()
    {
        config(['vanilo.cart.auto_assign_user' => false]);

        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);

        Cart::create();
        Cart::setUser($this->user);

        Auth::logout();
        $this->assertGuest();

        $this->assertEquals($this->user->id, Cart::getUser()->id);
    }

    public function setUp()
    {
        parent::setUp();

        $this->user = User::create([
            'email'    => 'ever@green.me',
            'name'     => 'Molly Green',
            'password' => bcrypt('brute force')
        ])->fresh();
    }
}
