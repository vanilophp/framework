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

use Illuminate\Support\Facades\Auth;
use Vanilo\Cart\Models\Cart as CartModel;
use Vanilo\Cart\Models\CartState;
use Vanilo\Cart\Tests\Dummies\Product;
use Vanilo\Cart\Tests\Dummies\User;
use Vanilo\Cart\Facades\Cart;

class PreserveForUserTest extends TestCase
{
    /** @var User */
    protected $user;

    /** @var Product */
    protected $product;

    protected function setUp(): void
    {
        parent::setUp();

        config(['vanilo.cart.preserve_for_user' => true]);

        $this->user = User::create([
            'email'    => 'dude.who@uses-devices.info',
            'name'     => 'Dude Who',
            'password' => bcrypt('123456 always works')
        ])->fresh();

        $this->product = Product::create([
            'name'  => 'myPad Ultra',
            'price' => '279'
        ])->fresh();
    }

    /** @test */
    public function it_preserves_the_cart_for_the_user_after_logout_if_feature_is_enabled()
    {
        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);

        Cart::addItem($this->product);
        $this->assertCount(1, Cart::getItems());

        Auth::logout();
        $this->assertGuest();

        $this->assertCount(1, CartModel::ofUser($this->user)->get());
    }

    /** @test */
    public function it_restores_the_cart_if_user_logs_back_in_and_feature_is_enabled()
    {
        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);

        Cart::addItem($this->product, 3);
        $this->assertCount(1, Cart::getItems());
        $this->assertEquals(3, Cart::getItems()->first()->quantity);

        Auth::logout();
        $this->assertGuest();

        $this->flushSession();
        $this->assertTrue(Cart::isEmpty());

        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);
        $this->assertTrue(Cart::isNotEmpty());
        $this->assertCount(1, Cart::getItems());
        $this->assertEquals(3, Cart::getItems()->first()->quantity);
    }

    /** @test */
    public function it_does_not_restore_the_saved_cart_if_there_is_another_cart_for_the_session()
    {
        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);

        // create cart (A)
        Cart::addItem($this->product, 3);
        $this->assertCount(1, Cart::getItems());
        $this->assertEquals(3, Cart::getItems()->first()->quantity);

        Auth::logout();
        $this->assertGuest();

        Cart::forget();
        $this->assertTrue(Cart::isEmpty());

        // Create another cart (B)
        Cart::addItem($this->product, 5);
        $this->assertTrue(Cart::isNotEmpty());
        $this->assertEquals(5, Cart::getItems()->first()->quantity);

        // log back in
        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);

        // see we still have cart B and cart A was not restored
        $this->assertTrue(Cart::isNotEmpty());
        $this->assertCount(1, Cart::getItems());
        $this->assertEquals(5, Cart::getItems()->first()->quantity);
    }

    /** @test */
    public function it_does_not_restore_a_checked_out_cart()
    {
        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);

        Cart::addItem($this->product);

        Cart::model()->update(['state' => CartState::COMPLETED]);
        Auth::logout();
        $this->assertGuest();

        Cart::forget();
        $this->assertTrue(Cart::isEmpty());

        $this->be($this->user);
        $this->assertAuthenticatedAs($this->user);

        $this->assertTrue(Cart::isEmpty());
    }
}
