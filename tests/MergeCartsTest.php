<?php
/**
 * Contains the MergeCartsTest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-08-25
 *
 */

namespace Vanilo\Cart\Tests;

use Illuminate\Support\Facades\Auth;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;
use Vanilo\Cart\Tests\Dummies\User;

class MergeCartsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['vanilo.cart.preserve_for_user' => true]);
    }

    /** @test */
    public function a_users_previous_cart_can_be_merged_with_the_current_sessions_cart()
    {
        config(['vanilo.cart.merge_duplicates' => true]);

        $user     = factory(User::class)->create();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();
        $product3 = factory(Product::class)->create();

        $this->be($user);
        $this->assertAuthenticatedAs($user);

        Cart::addItem($product1, 1);
        Cart::addItem($product2, 1);
        $this->assertCount(2, Cart::getItems());

        Auth::logout();
        $this->assertGuest();

        $this->flushSession();
        $this->assertTrue(Cart::isEmpty());

        Cart::addItem($product2, 1);
        Cart::addItem($product3, 1);
        $this->assertCount(2, Cart::getItems());
        $names = Cart::getItems()->map->product->pluck('name');
        $this->assertNotContains($product1->name, $names);
        $this->assertContains($product2->name, $names);
        $this->assertContains($product3->name, $names);

        $this->be($user);
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Cart::isNotEmpty());
        $this->assertCount(3, Cart::getItems());
        $names = Cart::getItems()->map->product->pluck('name');
        $this->assertContains($product1->name, $names);
        $this->assertContains($product2->name, $names);
        $this->assertContains($product3->name, $names);
    }

    /** @test */
    public function cart_will_not_be_merged_if_config_option_is_not_enabled()
    {
        config(['vanilo.cart.merge_duplicates' => false]);

        $user     = factory(User::class)->create();
        $product1 = factory(Product::class)->create();
        $product2 = factory(Product::class)->create();
        $product3 = factory(Product::class)->create();

        $this->be($user);
        $this->assertAuthenticatedAs($user);

        Cart::addItem($product1, 1);
        Cart::addItem($product2, 1);
        $this->assertCount(2, Cart::getItems());

        Auth::logout();
        $this->assertGuest();

        $this->flushSession();
        $this->assertTrue(Cart::isEmpty());

        Cart::addItem($product2, 1);
        Cart::addItem($product3, 1);
        $this->assertCount(2, Cart::getItems());
        $names = Cart::getItems()->map->product->pluck('name');
        $this->assertNotContains($product1->name, $names);
        $this->assertContains($product2->name, $names);
        $this->assertContains($product3->name, $names);

        $this->be($user);
        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Cart::isNotEmpty());
        $this->assertCount(2, Cart::getItems());
        $names = Cart::getItems()->map->product->pluck('name');
        $this->assertNotContains($product1->name, $names);
        $this->assertContains($product2->name, $names);
        $this->assertContains($product3->name, $names);
    }
}
