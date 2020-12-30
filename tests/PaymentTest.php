<?php

declare(strict_types=1);

/**
 * Contains the PaymentTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-28
 *
 */

namespace Vanilo\Payment\Tests;

use Konekt\Enum\Enum;
use Vanilo\Contracts\Payable;
use Vanilo\Payment\Contracts\Payment as PaymentContract;
use Vanilo\Payment\Contracts\PaymentStatus as PaymentStatusContract;
use Vanilo\Payment\Models\Payment;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\Models\PaymentStatus;
use Vanilo\Payment\Tests\Examples\Order;

class PaymentTest extends TestCase
{
    private $method;

    /** @test */
    public function it_can_be_created()
    {
        $payment = Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
        ]);

        $this->assertInstanceOf(Payment::class, $payment);
    }

    /** @test */
    public function it_implements_the_payment_contract()
    {
        $this->assertInstanceOf(PaymentContract::class, new Payment());
    }

    /** @test */
    public function status_field_is_an_enum()
    {
        $payment = new Payment();

        $this->assertInstanceOf(Enum::class, $payment->status);
    }

    /** @test */
    public function status_implements_the_payment_status_contract()
    {
        $payment = new Payment();

        $this->assertInstanceOf(PaymentStatusContract::class, $payment->status);
    }

    /** @test */
    public function payment_has_default_status_if_none_was_given()
    {
        $payment = new Payment();

        $this->assertEquals(PaymentStatus::defaultValue(), $payment->status->value());
    }

    /** @test */
    public function amount_paid_is_zero_by_default()
    {
        $payment = Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
        ]);

        $this->assertNotNull($payment->amount_paid);
        $this->assertEquals(0, $payment->amount_paid);
    }

    /** @test */
    public function basic_fields_can_be_assigned()
    {
        $payment = Payment::create([
            'amount' => 27,
            'amount_paid' => 21,
            'currency' => 'EUR',
            'status' => PaymentStatus::PARTIALLY_PAID(),
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
        ]);

        $this->assertEquals(27, $payment->amount);
        $this->assertEquals('EUR', $payment->currency);
        $this->assertEquals(21, $payment->amount_paid);
        $this->assertEquals(Order::class, $payment->payable_type);
        $this->assertEquals(1, $payment->payable_id);
        $this->assertEquals($this->method->id, $payment->payment_method_id);
        $this->assertTrue(PaymentStatus::PARTIALLY_PAID()->equals($payment->status));
    }

    /** @test */
    public function it_can_resolve_the_related_payable_entity()
    {
        $order = Order::create([
            'id' => 2592,
            'total' => 35
        ]);

        $payment = Payment::create([
            'amount' => 35,
            'currency' => 'EUR',
            'status' => PaymentStatus::PARTIALLY_PAID(),
            'payable_type' => Order::class,
            'payable_id' => $order->id,
            'payment_method_id' => $this->method->id,
        ]);

        $this->assertEquals(2592, $order->id);
        $this->assertInstanceOf(Order::class, $payment->payable);
        $this->assertEquals(2592, $payment->payable->id);
    }

    /** @test */
    public function the_related_payment_method_can_be_retrieved()
    {
        $payment = Payment::create([
            'amount' => 35,
            'currency' => 'EUR',
            'status' => PaymentStatus::PARTIALLY_PAID(),
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
        ]);

        $this->assertInstanceOf(PaymentMethod::class, $payment->method);
        $this->assertEquals($this->method->id, $payment->method->id);
    }

    /** @test */
    public function the_interface_getter_methods_return_the_appropriate_attributes()
    {
        $order = Order::create([
            'total' => 41
        ]);

        $payment = Payment::create([
            'amount' => 41,
            'amount_paid' => 40.99,
            'currency' => 'USD',
            'status' => PaymentStatus::PARTIALLY_PAID,
            'payable_type' => Order::class,
            'payable_id' => $order->id,
            'payment_method_id' => $this->method->id,
        ]);

        $this->assertEquals(41, $payment->getAmount());
        $this->assertEquals('USD', $payment->getCurrency());
        $this->assertEquals(40.99, $payment->getAmountPaid());
        $this->assertEquals(PaymentStatus::PARTIALLY_PAID, $payment->getStatus()->value());

        $this->assertInstanceOf(PaymentMethod::class, $payment->getMethod());
        $this->assertEquals($this->method->id, $payment->getMethod()->id);

        $this->assertInstanceOf(Order::class, $payment->getPayable());
        $this->assertInstanceOf(Payable::class, $payment->getPayable());
        $this->assertEquals($order->id, $payment->getPayable()->id);
    }

    /** @test */
    public function payment_hash_gets_automatically_generated()
    {
        $payment = new Payment();

        $this->assertIsString($payment->hash);
        $this->assertGreaterThan(0, strlen($payment->hash));
    }

    /** @test */
    public function the_payment_hash_is_21_chars_by_default()
    {
        $payment = new Payment();

        $this->assertIsString($payment->hash);
        $this->assertEquals(21, strlen($payment->hash));
    }

    /** @test */
    public function autogenerated_hashes_are_unique()
    {
        $numbers = [];

        for ($k = 0; $k < 1000; $k++) {
            $numbers[] = (new Payment())->hash;
        }

        $this->assertEquals(count($numbers), count(array_unique($numbers)));
    }

    /** @test */
    public function the_hash_can_be_explicitly_set()
    {
        $payment = new Payment([
            'hash' => 'Yo! This is a hash'
        ]);

        $this->assertEquals('Yo! This is a hash', $payment->hash);
    }

    /** @test */
    public function the_hash_can_be_explicitly_set_when_using_the_static_creator()
    {
        $payment = Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'hash' => 'Happy 2021!',
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
        ]);

        $this->assertEquals('Happy 2021!', $payment->hash);
    }

    /** @test */
    public function get_payment_id_returns_the_hash()
    {
        $payment = new Payment([
            'hash' => 'sunshine'
        ]);

        $this->assertEquals('sunshine', $payment->getPaymentId());
    }

    /** @test */
    public function payments_can_be_located_by_hash_with_the_static_finder_method()
    {
        $payment = Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'hash' => 'Rabbit & Apple',
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
        ]);

        $foundPayment = Payment::findByPaymentId('Rabbit & Apple');
        $this->assertInstanceOf(Payment::class, $foundPayment);
        $this->assertEquals('Rabbit & Apple', $foundPayment->getPaymentId());
        $this->assertEquals($payment->id, $foundPayment->id);
    }

    /** @test */
    public function the_static_finder_returns_null_if_no_item_was_found()
    {
        $this->assertNull(Payment::findByPaymentId('I do not exist'));
    }

    /** @test */
    public function extra_data_is_an_array()
    {
        $payment = new Payment();

        $this->assertEmpty($payment->getExtraData());
        $this->assertIsArray($payment->getExtraData());
    }

    /** @test */
    public function extra_data_can_be_set_as_array()
    {
        $payment = Payment::create([
            'amount' => 27,
            'currency' => 'EUR',
            'payable_type' => Order::class,
            'payable_id' => 1,
            'payment_method_id' => $this->method->id,
            'data' => ['meh' => 'moh']
        ]);

        $payment = $payment->fresh();
        $this->assertIsArray($payment->getExtraData());
        $this->assertEquals('moh', $payment->data['meh']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->method = $method = PaymentMethod::create([
            'name'    => 'Credit Card',
            'gateway' => 'plastic'
        ]);
    }
}
