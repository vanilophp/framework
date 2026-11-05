<?php

declare(strict_types=1);

/**
 * Contains the IssuedPaymentRequestTest class.
 *
 * @copyright   Copyright (c) 2026 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2026-11-05
 *
 */

namespace Vanilo\Payment\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Payment\Contracts\IssuedPaymentRequest as IssuedPaymentRequestContract;
use Vanilo\Payment\Contracts\PaymentRequest as PaymentRequestContract;
use Vanilo\Payment\Models\IssuedPaymentRequest;
use Vanilo\Payment\Models\IssuedPaymentRequestProxy;
use Vanilo\Payment\Models\Payment;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\Tests\Examples\Order;

class IssuedPaymentRequestTest extends TestCase
{
    #[Test] public function the_proxy_resolves_to_the_model()
    {
        $this->assertEquals(IssuedPaymentRequest::class, IssuedPaymentRequestProxy::modelClass());
    }

    #[Test] public function it_does_not_take_over_the_on_the_fly_payment_request_contract()
    {
        $this->assertInstanceOf(
            IssuedPaymentRequest::class,
            $this->app->make(IssuedPaymentRequestContract::class),
        );
        $this->assertFalse($this->app->bound(PaymentRequestContract::class));
    }

    #[Test] public function the_snapshot_is_optional()
    {
        $request = IssuedPaymentRequest::create(['payment_id' => $this->createPayment()->id])->fresh();

        $this->assertNull($request->snapshot_id);
    }

    #[Test] public function it_can_store_a_snapshot_id_without_an_existing_snapshot_record()
    {
        $request = IssuedPaymentRequest::create([
            'payment_id' => $this->createPayment()->id,
            'snapshot_id' => 123456,
        ])->fresh();

        $this->assertEquals(123456, $request->snapshot_id);
    }

    #[Test] public function it_belongs_to_a_payment()
    {
        $payment = $this->createPayment();
        $request = IssuedPaymentRequest::create(['payment_id' => $payment->id]);

        $this->assertInstanceOf(Payment::class, $request->payment);
        $this->assertEquals($payment->id, $request->payment->id);
    }

    #[Test] public function a_payment_can_have_multiple_issued_requests()
    {
        $payment = $this->createPayment();
        $other = $this->createPayment();

        $first = $payment->issuedRequests()->create();
        $second = $payment->issuedRequests()->create();
        $other->issuedRequests()->create();

        $this->assertCount(2, $payment->issuedRequests);
        $this->assertEqualsCanonicalizing(
            [$first->id, $second->id],
            $payment->issuedRequests->pluck('id')->all(),
        );
        $this->assertContainsOnlyInstancesOf(IssuedPaymentRequest::class, $payment->issuedRequests);
    }

    private function createPayment(): Payment
    {
        $order = Order::create(['total' => 29.99, 'currency' => 'EUR']);

        return Payment::create([
            'amount' => 29.99,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => $order->id,
            'payment_method_id' => PaymentMethod::create(['name' => 'Credit Card', 'gateway' => 'plastic'])->id,
        ]);
    }
}
