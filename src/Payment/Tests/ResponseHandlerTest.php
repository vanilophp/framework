<?php

declare(strict_types=1);

/**
 * Contains the ResponseHandlerTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-12
 *
 */

namespace Vanilo\Payment\Tests;

use Illuminate\Support\Facades\Event;
use Vanilo\Payment\Events\PaymentCompleted;
use Vanilo\Payment\Events\PaymentCreated;
use Vanilo\Payment\Events\PaymentDeclined;
use Vanilo\Payment\Events\PaymentPartiallyReceived;
use Vanilo\Payment\Events\PaymentTimedOut;
use Vanilo\Payment\Models\Payment;
use Vanilo\Payment\Models\PaymentHistory;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\Models\PaymentStatus;
use Vanilo\Payment\Processing\PaymentResponseHandler;
use Vanilo\Payment\Tests\Examples\Order;
use Vanilo\Payment\Tests\Examples\SomeNativeStatus;
use Vanilo\Payment\Tests\Examples\SomePaymentResponse;

class ResponseHandlerTest extends TestCase
{
    private PaymentMethod $method;

    protected function setUp(): void
    {
        parent::setUp();

        $this->method = $method = PaymentMethod::create([
            'name' => 'Credit Card',
            'gateway' => 'plastic'
        ]);
    }

    /** @test */
    public function it_updates_the_payment_status()
    {
        $payment = $this->createPayment(PaymentStatus::PENDING());
        $response = $this->createResponse(PaymentStatus::AUTHORIZED());
        $handler = new PaymentResponseHandler($payment, $response);

        $this->assertEquals(PaymentStatus::PENDING, $payment->status->value());

        $handler->updatePayment();

        $this->assertEquals(PaymentStatus::AUTHORIZED, $payment->status->value());
    }

    /** @test */
    public function it_updates_the_paid_amount()
    {
        $payment = $this->createPayment(PaymentStatus::PENDING());
        $response = $this->createResponse(PaymentStatus::PAID());
        $handler = new PaymentResponseHandler($payment, $response);

        $this->assertEquals(0, $payment->amount_paid);

        $handler->updatePayment();

        $this->assertEquals(27, $payment->amount_paid);
    }

    /** @test */
    public function it_updates_the_paid_amount_if_partial_payment_was_made()
    {
        $payment = $this->createPayment(PaymentStatus::PENDING());
        $response = $this->createResponse(PaymentStatus::PARTIALLY_PAID(), 19);
        $handler = new PaymentResponseHandler($payment, $response);

        $this->assertEquals(0, $payment->amount_paid);

        $handler->updatePayment();

        $this->assertEquals(19, $payment->amount_paid);
    }

    /** @test */
    public function it_subtracts_the_transaction_from_the_payments_paid_amount_if_the_responses_amount_is_negative()
    {
        $payment = $this->createPayment(PaymentStatus::PENDING());
        $handler = new PaymentResponseHandler($payment, $this->createResponse(PaymentStatus::PAID(), 27));

        $handler->updatePayment();
        $this->assertEquals(27, $payment->amount_paid);

        $handler = new PaymentResponseHandler($payment, $this->createResponse(PaymentStatus::REFUNDED(), -17));
        $handler->updatePayment();
        $this->assertEquals(10, $payment->amount_paid);
    }

    /** @test */
    public function two_consecutive_partial_payments_sum_up_the_payments_paid_amount()
    {
        $payment = $this->createPayment(PaymentStatus::PENDING());
        $handler = new PaymentResponseHandler($payment, $this->createResponse(PaymentStatus::PARTIALLY_PAID(), 15));

        $handler->updatePayment();
        $this->assertEquals(15, $payment->amount_paid);

        $handler = new PaymentResponseHandler($payment, $this->createResponse(PaymentStatus::PARTIALLY_PAID(), 12));
        $handler->updatePayment();
        $this->assertEquals(27, $payment->amount_paid);
    }

    /** @test */
    public function it_updates_the_status_message()
    {
        $payment = $this->createPayment(PaymentStatus::PENDING());
        $response = $this->createResponse(PaymentStatus::DECLINED(), 27, 'Not a success story');
        $handler = new PaymentResponseHandler($payment, $response);

        $this->assertNull($payment->status_message);

        $handler->updatePayment();

        $this->assertEquals('Not a success story', $payment->status_message);
    }

    /** @test */
    public function it_creates_payment_history_entries()
    {
        $payment = $this->createPayment(PaymentStatus::PENDING());
        $response = $this->createResponse(PaymentStatus::TIMEOUT(), null, 'Timed out like a lion');
        $handler = new PaymentResponseHandler($payment, $response);

        $this->assertEmpty($payment->history);

        $handler->writeResponseToHistory();

        $history = $payment->fresh()->history;
        $this->assertCount(1, $history);

        /** @var PaymentHistory $entry */
        $entry = $history->first();
        $this->assertEquals(PaymentStatus::TIMEOUT, $entry->new_status->value());
        $this->assertEquals(PaymentStatus::PENDING, $entry->old_status->value());
        $this->assertEquals('Timed out like a lion', $entry->message);
        $this->assertEquals(0, $entry->transaction_amount);
    }

    /** @test */
    public function it_fires_events_defined_in_the_default_mapper()
    {
        $payment = $this->createPayment(PaymentStatus::ON_HOLD());
        $response = $this->createResponse(PaymentStatus::DECLINED());
        $handler = new PaymentResponseHandler($payment, $response);

        Event::fake();
        $handler->fireEvents();
        Event::assertDispatched(PaymentDeclined::class);
    }

    /** @test */
    public function it_doesnt_fire_events_if_the_new_status_is_not_mapped_as_a_trigger()
    {
        $payment = $this->createPayment(PaymentStatus::PENDING());
        $response = $this->createResponse(PaymentStatus::ON_HOLD());
        $handler = new PaymentResponseHandler($payment, $response);

        Event::fake();
        $handler->fireEvents();
        Event::assertNotDispatched(PaymentCompleted::class);
        Event::assertNotDispatched(PaymentCreated::class);
        Event::assertNotDispatched(PaymentDeclined::class);
        Event::assertNotDispatched(PaymentPartiallyReceived::class);
        Event::assertNotDispatched(PaymentTimedOut::class);
    }

    /** @test */
    public function it_doesnt_fire_events_if_the_new_status_is_mapped_as_a_trigger_but_the_old_status_is_excluded()
    {
        $payment = $this->createPayment(PaymentStatus::CANCELLED());
        $response = $this->createResponse(PaymentStatus::DECLINED());
        $handler = new PaymentResponseHandler($payment, $response);

        Event::fake();
        $handler->fireEvents();
        Event::assertNotDispatched(PaymentDeclined::class);
    }

    private function createPayment(PaymentStatus $status): Payment
    {
        return Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 1,
            'status' => $status,
            'payment_method_id' => $this->method->id,
        ]);
    }

    private function createResponse(PaymentStatus $status, ?float $amountPaid = 27, $message = 'Transaction OK'): SomePaymentResponse
    {
        return new SomePaymentResponse($message, true, '123', $amountPaid, '123', SomeNativeStatus::CAPTURED(), $status);
    }
}
