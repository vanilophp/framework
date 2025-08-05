<?php

declare(strict_types=1);

/**
 * Contains the RemoteIdTest class.
 *
 * @copyright   Copyright (c) 2021 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2021-05-20
 *
 */

namespace Vanilo\Payment\Tests;

use Illuminate\Database\QueryException;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Payment\Models\Payment;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\Tests\Examples\Order;

class RemoteIdTest extends TestCase
{
    private PaymentMethod $method;

    protected function setUp(): void
    {
        parent::setUp();

        $this->method = PaymentMethod::create([
            'name' => 'What Blah',
            'gateway' => 'meh'
        ]);
    }

    #[Test] public function remote_id_is_optional()
    {
        $payment = Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
        ]);

        $this->assertNull($payment->remote_id);
    }

    #[Test] public function multiple_records_can_have_null_as_remote_id()
    {
        $payment1 = Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
        ]);

        $payment2 = Payment::create([
            'amount' => 35,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 2,
            'payment_method_id' => $this->method->id,
        ]);

        $payment3 = Payment::create([
            'amount' => 43,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 3,
            'payment_method_id' => $this->method->id,
        ]);

        $this->assertNull($payment1->remote_id);
        $this->assertNull($payment2->remote_id);
        $this->assertNull($payment3->remote_id);
    }

    #[Test] public function the_remote_id_must_be_unique_within_a_payment_method()
    {
        Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
            'remote_id' => 'ABC'
        ]);

        $this->expectException(QueryException::class);
        $this->expectExceptionMessageMatches('/.*UNIQUE.*/');

        Payment::create([
            'amount' => 35,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 2,
            'payment_method_id' => $this->method->id,
            'remote_id' => 'ABC'
        ]);
    }

    #[Test] public function the_same_remote_id_can_be_used_with_another_payment_method()
    {
        $payment1 = Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
            'remote_id' => 'ABC'
        ]);

        $payment2 = Payment::create([
            'amount' => 35,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 2,
            'payment_method_id' => PaymentMethod::create(['name' => 'A', 'gateway' => 'a'])->id,
            'remote_id' => 'ABC'
        ]);

        $this->assertEquals('ABC', $payment1->remote_id);
        $this->assertEquals('ABC', $payment2->remote_id);
    }

    #[Test] public function a_payment_can_be_fetched_by_its_remote_id_without_specifying_the_payment_method_id()
    {
        Payment::create([
            'amount' => 42.10,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
            'remote_id' => '91458516NG935120K'
        ]);

        $payment = Payment::findByRemoteId('91458516NG935120K');

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals(42.10, $payment->amount);
        $this->assertEquals('EUR', $payment->currency);
        $this->assertEquals(1, $payment->payable_id);
        $this->assertEquals(Order::class, $payment->payable_type);
        $this->assertEquals($this->method->id, $payment->payment_method_id);
        $this->assertEquals('91458516NG935120K', $payment->remote_id);
    }

    #[Test] public function payment_can_be_retrieved_by_remote_id_and_payment_method_id()
    {
        $anotherPaymentMethod = PaymentMethod::create(['name' => 'A', 'gateway' => 'a']);

        Payment::create([
            'amount' => 58.50,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 566,
            'payment_method_id' => $this->method->id,
            'remote_id' => '2VW98478AK593801D'
        ]);

        Payment::create([
            'amount' => 79.95,
            'currency' => 'USD',
            'payable_type' => Order::class,
            'payable_id' => 567,
            'payment_method_id' => $anotherPaymentMethod->id,
            'remote_id' => '2VW98478AK593801D'
        ]);

        $payment1 = Payment::findByRemoteId('2VW98478AK593801D', $this->method->id);
        $payment2 = Payment::findByRemoteId('2VW98478AK593801D', $anotherPaymentMethod->id);

        $this->assertEquals(58.50, $payment1->amount);
        $this->assertEquals(566, $payment1->payable_id);
        $this->assertEquals('2VW98478AK593801D', $payment1->remote_id);

        $this->assertEquals(79.95, $payment2->amount);
        $this->assertEquals(567, $payment2->payable_id);
        $this->assertEquals('2VW98478AK593801D', $payment2->remote_id);
    }
}
