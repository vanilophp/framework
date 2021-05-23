<?php

declare(strict_types=1);

/**
 * Contains the HistoryTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-03-21
 *
 */

namespace Vanilo\Payment\Tests;

use Vanilo\Payment\Contracts\PaymentHistory as PaymentHistoryContract;
use Vanilo\Payment\Models\Payment;
use Vanilo\Payment\Models\PaymentHistory;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\Models\PaymentStatus;
use Vanilo\Payment\Tests\Examples\Order;
use Vanilo\Payment\Tests\Examples\SomeNativeStatus;
use Vanilo\Payment\Tests\Examples\SomePaymentResponse;

class HistoryTest extends TestCase
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
    public function it_can_be_created_with_minimal_fields()
    {
        $payment = $this->createPayment();

        $entry = PaymentHistory::create([
            'payment_id' => $payment->id,
            'new_status' => PaymentStatus::DECLINED,
        ]);

        $this->assertInstanceOf(PaymentHistory::class, $entry);
        $this->assertInstanceOf(PaymentHistoryContract::class, $entry);
    }

    /** @test */
    public function associated_payment_can_be_retrieved()
    {
        $payment = $this->createPayment();

        $entry = PaymentHistory::create([
            'payment_id' => $payment->id,
            'new_status' => PaymentStatus::AUTHORIZED,
        ]);

        $this->assertInstanceOf(Payment::class, $entry->payment);
    }

    /** @test */
    public function new_status_is_a_payment_status_enum()
    {
        $payment = $this->createPayment();

        $entry = PaymentHistory::create([
            'payment_id' => $payment->id,
            'new_status' => PaymentStatus::AUTHORIZED,
        ]);

        $this->assertInstanceOf(PaymentStatus::class, $entry->new_status);
    }

    /** @test */
    public function old_status_is_a_payment_status_enum()
    {
        $payment = $this->createPayment();

        $entry = PaymentHistory::create([
            'payment_id' => $payment->id,
            'old_status' => PaymentStatus::PENDING,
            'new_status' => PaymentStatus::PAID,
        ]);

        $this->assertInstanceOf(PaymentStatus::class, $entry->old_status);
    }

    /** @test */
    public function all_fields_can_be_written()
    {
        $payment = $this->createPayment(19.99);

        $entry = PaymentHistory::create([
            'payment_id' => $payment->id,
            'old_status' => PaymentStatus::AUTHORIZED(),
            'new_status' => PaymentStatus::PAID(),
            'message' => 'Payment captured',
            'native_status' => 'captured',
            'transaction_amount' => 19.99,
            'transaction_number' => '7sig72jf9hduvbcsuj02jdafxvb1sahkrjagf',
        ]);

        $this->assertEquals($payment->id, $entry->payment_id);
        $this->assertEquals(PaymentStatus::AUTHORIZED, $entry->old_status->value());
        $this->assertEquals(PaymentStatus::PAID, $entry->new_status->value());
        $this->assertEquals('Payment captured', $entry->message);
        $this->assertEquals('captured', $entry->native_status);
        $this->assertEquals(19.99, $entry->transaction_amount);
        $this->assertEquals('7sig72jf9hduvbcsuj02jdafxvb1sahkrjagf', $entry->transaction_number);
    }

    /** @test */
    public function it_can_be_begun_with_the_dedicated_method()
    {
        $payment = $this->createPayment(55.17);
        $entry = PaymentHistory::begin($payment);
        $this->assertEquals($payment->id, $entry->payment_id);
        $this->assertEquals($payment->status->value(), $entry->new_status->value());
        $this->assertEquals('Payment created', $entry->message);
        $this->assertNull($entry->native_status);
        $this->assertEquals(0, $entry->transaction_amount);
        $this->assertNull($entry->transaction_number);
    }

    /** @test */
    public function it_can_be_written_from_a_payment_response()
    {
        $payment = $this->createPayment(63.99);
        $paymentResponse = new SomePaymentResponse(
            'Payment has been captured',
            true,
            'pampampam',
            63.99,
            $payment->getPaymentId(),
            SomeNativeStatus::CAPTURED(),
            PaymentStatus::PAID()
        );
        $entry = PaymentHistory::addPaymentResponse($payment, $paymentResponse);

        $this->assertEquals($payment->id, $entry->payment_id);
        $this->assertEquals($payment->status->value(), $entry->old_status->value());
        $this->assertEquals(PaymentStatus::PAID, $entry->new_status->value());
        $this->assertEquals('Payment has been captured', $entry->message);
        $this->assertEquals(SomeNativeStatus::CAPTURED, $entry->native_status);
        $this->assertEquals(63.99, $entry->transaction_amount);
        $this->assertEquals('pampampam', $entry->transaction_number);
    }

    /** @test */
    public function the_add_event_static_factory_method_can_be_used_to_log_non_status_changing_events()
    {
        $payment = $this->createPayment(55.00);
        $entry = PaymentHistory::addEvent($payment, 'Yo! This is a message.');

        $this->assertEquals($payment->id, $entry->payment_id);
        $this->assertEquals($payment->status->value(), $entry->old_status->value());
        $this->assertEquals($payment->status->value(), $entry->new_status->value());
        $this->assertEquals('Yo! This is a message.', $entry->message);
        $this->assertNull($entry->native_status);
        $this->assertNull($entry->transaction_amount);
        $this->assertNull($entry->transaction_number);
    }

    /** @test */
    public function the_transaction_number_and_the_native_status_can_be_explicitly_logged_with_the_add_event_static_factory_method()
    {
        $payment = $this->createPayment(107);
        $entry = PaymentHistory::addEvent($payment, 'Log entry with extras', 'trid19192234194', SomeNativeStatus::CAPTURED());

        $this->assertEquals($payment->id, $entry->payment_id);
        $this->assertEquals($payment->status->value(), $entry->old_status->value());
        $this->assertEquals($payment->status->value(), $entry->new_status->value());
        $this->assertEquals('Log entry with extras', $entry->message);
        $this->assertEquals(SomeNativeStatus::CAPTURED, $entry->native_status);
        $this->assertNull($entry->transaction_amount);
        $this->assertEquals('trid19192234194', $entry->transaction_number);
    }

    /** @test */
    public function old_status_can_be_explicitly_specified_at_write_from_response_method()
    {
        $payment = $this->createPayment();
        $paymentResponse = new SomePaymentResponse('', true, 'x', 3.99, $payment->getPaymentId(), SomeNativeStatus::CAPTURED(), PaymentStatus::AUTHORIZED());
        $entry = PaymentHistory::addPaymentResponse($payment, $paymentResponse, PaymentStatus::DECLINED());

        $this->assertEquals(PaymentStatus::AUTHORIZED, $entry->new_status->value());
        $this->assertEquals(PaymentStatus::DECLINED, $entry->old_status->value());
    }

    /** @test */
    public function payment_can_return_its_history_entries()
    {
        $payment1 = $this->createPayment(19, 'EUR');
        $payment2 = $this->createPayment(38, 'EUR');

        $payment1Statuses = [PaymentStatus::DECLINED, PaymentStatus::AUTHORIZED, PaymentStatus::PAID];
        $payment2Statuses = [PaymentStatus::PAID, PaymentStatus::REFUNDED];

        foreach ($payment1Statuses as $status) {
            PaymentHistory::create([
                'payment_id' => $payment1->id,
                'new_status' => $status,
            ]);
            $payment1->status = $status;
            $payment1->save();
        }

        foreach ($payment2Statuses as $status) {
            PaymentHistory::create([
                'payment_id' => $payment2->id,
                'new_status' => $status,
            ]);
            $payment2->status = $status;
            $payment2->save();
        }

        $this->assertCount(3, $payment1->history);
        $this->assertEquals(
            $payment1Statuses,
            $payment1->history->pluck('new_status')->map(fn ($status) => $status->value())->all()
        );
        $this->assertCount(2, $payment2->history);
        $this->assertEquals(
            $payment2Statuses,
            $payment2->history->pluck('new_status')->map(fn ($status) => $status->value())->all()
        );
    }

    private function createPayment(float $amount = 59, string $currency = 'USD'): Payment
    {
        return Payment::create([
            'amount' => $amount,
            'currency' => $currency,
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
        ]);
    }
}
