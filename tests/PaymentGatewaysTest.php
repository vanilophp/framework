<?php
/**
 * Contains the PropertyTypeTest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-26
 *
 */

namespace Vanilo\Payment\Tests;

use Vanilo\Payment\PaymentGateways;
use Vanilo\Payment\Tests\Examples\PlasticPayments;

class PaymentGatewaysTest extends TestCase
{
    /** @test */
    public function new_gateways_can_be_registered()
    {
        PaymentGateways::register('plastic', PlasticPayments::class);

        $this->assertCount(1, PaymentGateways::choices());
    }

    /** @test */
    public function attempting_to_retrieve_an_unregistered_gateway_returns_null()
    {
        $this->assertNull(PaymentGateways::getClass('randomness'));
    }

    /** @test */
    public function registering_a_gateway_without_implementing_the_interface_is_not_allowed()
    {
        $this->expectException(\InvalidArgumentException::class);
        PaymentGateways::register('whatever', \stdClass::class);
    }
}
