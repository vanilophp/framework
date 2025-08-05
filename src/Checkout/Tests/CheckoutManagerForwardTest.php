<?php

declare(strict_types=1);

/**
 * Contains the CheckoutManagerForwardTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-11-09
 *
 */

namespace Vanilo\Checkout\Tests;

use BadMethodCallException;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Checkout\CheckoutManager;
use Vanilo\Checkout\Tests\Example\MemoryStore;

class CheckoutManagerForwardTest extends TestCase
{
    #[Test] public function it_forwards_unknown_method_calls_to_the_underlying_checkout_store()
    {
        $checkout = new CheckoutManager(new MemoryStore());

        $this->assertEquals('Hey I am a custom method', $checkout->customMethod());
    }

    #[Test] public function it_throws_an_exception_if_the_store_has_no_method_of_the_given_name()
    {
        $checkout = new CheckoutManager(new MemoryStore());

        $this->expectException(BadMethodCallException::class);
        $checkout->nonExistingMethod();
    }

    #[Test] public function it_returns_the_stores_attributes()
    {
        $checkout = new CheckoutManager(new MemoryStore());

        $this->assertEquals('Hey I am a store attribute', $checkout->storeAttribute);
    }

    #[Test] public function the_stores_magic_properties_are_returned_as_well()
    {
        $checkout = new CheckoutManager(new MemoryStore());

        $this->assertEquals('Hey I am a magic store attribute', $checkout->magicStoreAttribute);
    }
}
