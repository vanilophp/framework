<?php

declare(strict_types=1);

/**
 * Contains the ShippingMethodSelectedEventTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Checkout\Tests;

use Illuminate\Support\Facades\Event;
use Vanilo\Checkout\Drivers\RequestStore;
use Vanilo\Checkout\Drivers\SessionStore;
use Vanilo\Checkout\Events\ShippingMethodSelected;
use Vanilo\Checkout\Tests\Example\DataFactory;

class ShippingMethodSelectedEventTest extends TestCase
{
    /** @test */
    public function the_event_gets_fired_if_a_shipping_method_gets_set()
    {
        Event::fake();

        $checkout = new SessionStore(new DataFactory());
        $checkout->setShippingMethodId(3);

        Event::assertDispatched(function (ShippingMethodSelected $event) {
            return is_null($event->oldShippingMethodId())
                && 3 === $event->selectedShippingMethodId();
        });
    }

    /** @test */
    public function the_event_does_not_get_fired_if_setting_the_shipping_method_to_the_same_value_as_it_was_before()
    {
        $checkout = new SessionStore(new DataFactory());
        $checkout->setShippingMethodId(3);

        Event::fake();

        $checkout->setShippingMethodId(3);

        Event::assertNotDispatched(ShippingMethodSelected::class);
    }

    /** @test */
    public function the_event_gets_fired_when_nulling_a_non_null_shipping_method()
    {
        $checkout = new SessionStore(new DataFactory());
        $checkout->setShippingMethodId(3);

        Event::fake();

        $checkout->setShippingMethodId(null);

        Event::assertDispatched(ShippingMethodSelected::class);
    }

    /** @test */
    public function it_works_with_the_request_store_as_well()
    {
        $checkout = new RequestStore([], new DataFactory());
        Event::fake();
        $checkout->setShippingMethodId(3);

        Event::assertDispatched(function (ShippingMethodSelected $event) {
            return is_null($event->oldShippingMethodId())
                && 3 === $event->selectedShippingMethodId();
        });
    }
}
