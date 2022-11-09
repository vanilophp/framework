<?php

declare(strict_types=1);

/**
 * Contains the SessionStoreTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-03-30
 *
 */

namespace Vanilo\Checkout\Tests;

use Vanilo\Checkout\Contracts\CheckoutStore;
use Vanilo\Checkout\Drivers\SessionStore;
use Vanilo\Checkout\Models\CheckoutState;
use Vanilo\Checkout\Tests\Example\Cart;
use Vanilo\Checkout\Tests\Example\DataFactory;

class SessionStoreTest extends TestCase
{
    /** @test */
    public function it_can_be_instantiated()
    {
        $store = new SessionStore(new DataFactory());

        $this->assertInstanceOf(SessionStore::class, $store);
        $this->assertInstanceOf(CheckoutStore::class, $store);
    }

    /** @test */
    public function it_works_with_an_explicitly_passed_array_driver()
    {
        $this->assertInstanceOf(
            CheckoutStore::class,
            new SessionStore(new DataFactory(), session()->driver('array'))
        );
    }

    /** @test */
    public function a_cart_can_be_assigned_to()
    {
        $cart = new Cart();
        $store = new SessionStore(new DataFactory());
        $store->setCart($cart);

        $this->assertEquals($cart, $store->getCart());
    }

    /** @test */
    public function it_is_in_virgin_state_by_default()
    {
        $this->assertEquals(CheckoutState::VIRGIN(), (new SessionStore(new DataFactory()))->getState());
    }

    /** @test */
    public function state_can_be_set()
    {
        $store = new SessionStore(new DataFactory());
        $store->setState(CheckoutState::READY);

        $this->assertEquals(CheckoutState::READY(), $store->getState());
    }

    /** @test */
    public function custom_attributes_can_be_assigned()
    {
        $store = new SessionStore(new DataFactory());
        $store->setCustomAttribute('mama', 'mia');

        $this->assertEquals('mia', $store->getCustomAttribute('mama'));
    }

    /** @test */
    public function it_persists_its_data_in_the_session()
    {
        $fileSessionStore = session()->driver('file');

        $store = new SessionStore(new DataFactory(), $fileSessionStore);
        $store->setState(CheckoutState::READY);
        $store->setCustomAttribute('Giovanni', 'Gatto');
        $storeAtALaterPoint = new SessionStore(new DataFactory(), $fileSessionStore);

        $this->assertEquals(CheckoutState::READY(), $storeAtALaterPoint->getState());
        $this->assertEquals('Gatto', $storeAtALaterPoint->getCustomAttribute('Giovanni'));
    }
}
