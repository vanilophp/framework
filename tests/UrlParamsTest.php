<?php

declare(strict_types=1);

/**
 * Contains the UrlParamsTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-19
 *
 */

namespace Vanilo\Payment\Tests;

use Vanilo\Payment\Models\Payment;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\Models\PaymentStatus;
use Vanilo\Payment\Tests\Examples\Order;
use Vanilo\Payment\Tests\Examples\SomeUrlProcessor;

class UrlParamsTest extends TestCase
{
    private $method;

    protected function setUp(): void
    {
        parent::setUp();

        $this->method = $method = PaymentMethod::create([
            'name' => 'Credit Card',
            'gateway' => 'plastic'
        ]);
    }

    /** @test */
    public function it_replaces_the_payment_id_in_the_path()
    {
        $payment = $this->createATestPayment();
        $processor = new SomeUrlProcessor();

        $this->assertEquals(
            'http://vanilo.shop/return/' . $payment->getPaymentId(),
            $processor->processUrl('http://vanilo.shop/return/{paymentId}', $payment)
        );

        $this->assertEquals(
            'http://vanilo.shop/plastic/' . $payment->getPaymentId() . '/return',
            $processor->processUrl('http://vanilo.shop/plastic/{paymentId}/return', $payment)
        );
    }

    /** @test */
    public function it_replaces_the_payment_id_in_the_query()
    {
        $payment = $this->createATestPayment();
        $processor = new SomeUrlProcessor();

        $this->assertEquals(
            'https://vanilo.shop/return/plastic?pmid=' . $payment->getPaymentId(),
            $processor->processUrl('https://vanilo.shop/return/plastic?pmid={paymentId}', $payment)
        );

        $this->assertEquals(
            sprintf('https://vanilo.shop/return/plastic?pmid=%s&other=xyz', $payment->getPaymentId()),
            $processor->processUrl('https://vanilo.shop/return/plastic?pmid={paymentId}&other=xyz', $payment)
        );

        $this->assertEquals(
            sprintf('https://vanilo.shop/return/plastic?pmid=ABC123&paymentId=%s', $payment->getPaymentId()),
            $processor->processUrl('https://vanilo.shop/return/plastic?pmid=ABC123&paymentId={paymentId}', $payment)
        );
    }

    /** @test */
    public function it_replaces_the_payable_id_in_the_path()
    {
        $payment = $this->createATestPayment('6672');
        $processor = new SomeUrlProcessor();

        $this->assertEquals(
            'http://vanilo.shop/return/' . $payment->getPayable()->getPayableId(),
            $processor->processUrl('http://vanilo.shop/return/6672', $payment)
        );

        $this->assertEquals(
            'http://vanilo.shop/plastic/' . $payment->getPayable()->getPayableId() . '/return',
            $processor->processUrl('http://vanilo.shop/plastic/6672/return', $payment)
        );
    }

    /** @test */
    public function it_replaces_the_payable_id_in_the_query()
    {
        $payment = $this->createATestPayment('2172');
        $processor = new SomeUrlProcessor();

        $this->assertEquals(
            'https://vanilo.shop/return/plastic?order=2172',
            $processor->processUrl('https://vanilo.shop/return/plastic?order={payableId}', $payment)
        );

        $this->assertEquals(
            'https://vanilo.shop/return/plastic?oid=2172&other=xyz',
            $processor->processUrl('https://vanilo.shop/return/plastic?oid={payableId}&other=xyz', $payment)
        );

        $this->assertEquals(
            'https://vanilo.shop/return/plastic?pmid=ABC123&payableId=2172',
            $processor->processUrl('https://vanilo.shop/return/plastic?pmid=ABC123&payableId={payableId}', $payment)
        );
    }

    /** @test */
    public function it_leaves_alone_urls_without_parameters()
    {
        $payment = $this->createATestPayment();
        $processor = new SomeUrlProcessor();

        $this->assertEquals(
            'https://mde.xyz/qwe/asd',
            $processor->processUrl('https://mde.xyz/qwe/asd', $payment)
        );
    }

    /** @test */
    public function it_converts_paths_to_full_urls()
    {
        $payment = $this->createATestPayment();
        $processor = new SomeUrlProcessor();

        $this->assertEquals(
            'http://localhost/return/' . $payment->getPaymentId(),
            $processor->processUrl('/return/{paymentId}', $payment)
        );
    }

    /** @test */
    public function it_converts_paths_to_full_urls_even_without_parameters()
    {
        $payment = $this->createATestPayment();
        $processor = new SomeUrlProcessor();

        $this->assertEquals(
            'http://localhost/return/abcdefg',
            $processor->processUrl('/return/abcdefg', $payment)
        );
    }

    /** @test */
    public function it_preserves_query_parameters_when_path_gets_converted_to_url()
    {
        $payment = $this->createATestPayment('444');
        $processor = new SomeUrlProcessor();

        $this->assertEquals(
            'http://localhost/return?orderid=444',
            $processor->processUrl('/return?orderid={payableId}', $payment)
        );
    }

    private function createATestPayment(string $orderId = null): Payment
    {
        $order = Order::create([
            'total' => 35,
            'id' => $orderId,
        ]);

        return Payment::create([
            'amount' => 35,
            'currency' => 'EUR',
            'status' => PaymentStatus::PARTIALLY_PAID(),
            'payable_type' => Order::class,
            'payable_id' => $order->id,
            'payment_method_id' => $this->method->id,
        ]);
    }
}
