<?php

declare(strict_types=1);

/**
 * Contains the GatewaysTest class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-12-26
 *
 */

namespace Vanilo\Payment\Tests;

use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Models\PaymentStatus;
use Vanilo\Payment\PaymentGateways;
use Vanilo\Payment\Tests\Examples\PlasticPayments;
use Vanilo\Payment\Tests\Examples\UnorthodoxGateway;
use Vanilo\Payment\Tests\Examples\UnorthodoxPaymentResponse;

class GatewaysTest extends TestCase
{
    /** @test */
    public function new_gateways_can_be_registered()
    {
        $originalCount = count(PaymentGateways::choices());
        PaymentGateways::register('plastic', PlasticPayments::class);

        $this->assertCount($originalCount + 1, PaymentGateways::choices());
    }

    /** @test */
    public function registered_gateway_instances_can_be_returned()
    {
        PaymentGateways::register('plastic', PlasticPayments::class);

        $this->assertInstanceOf(PlasticPayments::class, PaymentGateways::make('plastic'));
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

    /** @test */
    public function non_illuminate_request_type_request_objects_can_be_accepted_by_process_payment_response_using_type_extension()
    {
        PaymentGateways::register('unorthodox', UnorthodoxGateway::class);
        $gw = PaymentGateways::make('unorthodox');

        $remoteResult = new UnorthodoxPaymentResponse('C9278', 27.99);
        $result = $gw->processPaymentResponse($remoteResult);

        $this->assertInstanceOf(PaymentResponse::class, $result);
        $this->assertEquals(PaymentStatus::PAID(), $result->getStatus());
        $this->assertEquals(27.99, $result->getTransactionAmount());
        $this->assertEquals('C9278', $result->getTransactionId());
    }
}
