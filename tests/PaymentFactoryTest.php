<?php

declare(strict_types=1);

/**
 * Contains the PaymentFactoryTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-30
 *
 */

namespace Vanilo\Payment\Tests;

use Vanilo\Payment\Factories\PaymentFactory;
use Vanilo\Payment\Models\Payment;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\Tests\Examples\Order;

class PaymentFactoryTest extends TestCase
{
    /** @var PaymentMethod */
    private $paymentMethod;

    /** @test */
    public function it_can_create_a_payment_from_a_payable_and_from_a_payment_method()
    {
        $order = Order::create(['total' => 179]);
        $payment = PaymentFactory::createFromPayable($order, $this->paymentMethod);

        $this->assertInstanceOf(Payment::class, $payment);
    }

    /** @test */
    public function payable_attributes_are_copied_to_the_payment()
    {
        /** @var Order $order */
        $order = Order::create(['total' => 2899.79]);
        $payment = PaymentFactory::createFromPayable($order, $this->paymentMethod);

        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals(2899.79, $payment->getAmount());
        $this->assertEquals($order->getCurrency(), $payment->getCurrency());
        $this->assertEquals($order->fresh(), $payment->getPayable());
    }

    /** @test */
    public function payment_method_gets_assigned_to_the_payment()
    {
        /** @var Order $order */
        $order = Order::create(['total' => 69.99]);
        $payment = PaymentFactory::createFromPayable($order, $this->paymentMethod);

        $this->assertInstanceOf(PaymentMethod::class, $payment->getMethod());
        $this->assertEquals($this->paymentMethod->fresh(), $payment->getMethod());
    }

    /** @test */
    public function extra_data_can_be_passed()
    {
        $order = Order::create(['total' => 2900]);

        $payment = PaymentFactory::createFromPayable($order, $this->paymentMethod, ['boo' => 'baa']);
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals('baa', $payment->getExtraData()['boo']);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->paymentMethod = PaymentMethod::create([
            'name'    => 'Credit Card',
            'gateway' => 'plastic',
        ]);
    }
}
