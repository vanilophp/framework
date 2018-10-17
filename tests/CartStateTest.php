<?php
/**
 * Contains the CartStateTest class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-10-15
 *
 */

namespace Vanilo\Cart\Tests;

use Konekt\Enum\Enum;
use Vanilo\Cart\Contracts\CartState as CartStateContract;
use Vanilo\Cart\Models\Cart;
use Vanilo\Cart\Models\CartState;

class CartStateTest extends TestCase
{
    /** @test */
    public function cart_state_is_an_enum_and_implements_the_cart_state_interface()
    {
        $cart = Cart::create([]);

        $this->assertInstanceOf(CartStateContract::class, $cart->state);
        $this->assertInstanceOf(Enum::class, $cart->state);
    }

    /** @test */
    public function a_new_cart_has_the_default_state()
    {
        $cart = Cart::create([]);

        $this->assertEquals(CartState::defaultValue(), $cart->state->value());
    }

    /** @test */
    public function the_cart_state_can_be_changed()
    {
        $cart = Cart::create(['state' => CartState::CHECKOUT]);
        $this->assertTrue(CartState::CHECKOUT()->equals($cart->state));

        $cart->state = CartState::ABANDONDED();
        $this->assertEquals(CartState::ABANDONDED, $cart->state->value());

        $cart->state = CartState::COMPLETED;
        $this->assertTrue(CartState::COMPLETED()->equals($cart->state));
    }

    /** @test */
    public function active_states_can_be_retrieved()
    {
        $this->assertCount(2, CartState::getActiveStates());

        $this->assertContains(CartState::ACTIVE, CartState::getActiveStates());
        $this->assertContains(CartState::CHECKOUT, CartState::getActiveStates());
    }

    /** @test */
    public function active_carts_can_be_retrieved()
    {
        Cart::create(['state' => CartState::ACTIVE]);
        Cart::create(['state' => CartState::ACTIVE]);
        Cart::create(['state' => CartState::CHECKOUT]);
        Cart::create(['state' => CartState::ABANDONDED]);
        Cart::create(['state' => CartState::COMPLETED]);

        $this->assertCount(5, Cart::all());
        $this->assertCount(3, Cart::actives()->get());
    }
}
