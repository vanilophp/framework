<?php

declare(strict_types=1);

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
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;
use Vanilo\Cart\Tests\Dummies\User;
use Vanilo\Cart\Tests\Factories\ProductFactory;
use Vanilo\Cart\Tests\Factories\UserFactory;

class MergeCartsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config(['vanilo.cart.preserve_for_user' => true]);
    }

    #[Test] public function a_users_previous_cart_can_be_merged_with_the_current_sessions_cart()
    {
        config(['vanilo.cart.merge_duplicates' => true]);

        /** @var User $user */
        $user = UserFactory::new()->create();
        /** @var Product $product1 */
        $product1 = ProductFactory::new()->create();;
        /** @var Product $product2 */
        $product2 = ProductFactory::new()->create();;
        /** @var Product $product3 */
        $product3 = ProductFactory::new()->create();;

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

    #[Test] public function cart_will_not_be_merged_if_config_option_is_not_enabled()
    {
        config(['vanilo.cart.merge_duplicates' => false]);

        $user = UserFactory::new()->create();
        $product1 = ProductFactory::new()->create();;
        $product2 = ProductFactory::new()->create();;
        $product3 = ProductFactory::new()->create();;

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
