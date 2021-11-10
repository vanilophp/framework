<?php

declare(strict_types=1);

/**
 * Contains the PaymentsTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-30
 *
 */

namespace Vanilo\Foundation\Tests;

use Illuminate\Support\Facades\DB;
use Vanilo\Foundation\Models\Order;
use Vanilo\Payment\Factories\PaymentFactory;
use Vanilo\Payment\Models\Payment;
use Vanilo\Payment\Models\PaymentMethod;

class PaymentsTest extends TestCase
{
    private PaymentMethod $paymentMethod;

    protected function setUp(): void
    {
        parent::setUp();
        Order::query()->delete();

        $this->paymentMethod = PaymentMethod::create([
            'name' => 'Wire Transfer',
            'gateway' => 'null',
        ]);
    }

    /** @test */
    public function an_order_can_have_multiple_payments()
    {
        $order = Order::create([
            'number' => '5732952',
        ]);

        $payment1 = PaymentFactory::createFromPayable($order, $this->paymentMethod);
        $payment2 = PaymentFactory::createFromPayable($order, $this->paymentMethod);

        $this->assertCount(2, $order->payments);
    }

    /** @test */
    public function the_current_payment_on_orders_returns_the_latest_payment_associated()
    {
        /** @var Order $order */
        $order = Order::create([
            'number' => '5732953',
        ]);

        PaymentFactory::createFromPayable($order, $this->paymentMethod, ['x' => 'a']);
        PaymentFactory::createFromPayable($order, $this->paymentMethod, ['x' => 'c']);
        PaymentFactory::createFromPayable($order, $this->paymentMethod, ['x' => 'b']);

        $payment = $order->getCurrentPayment();
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals('b', $payment->getExtraData()['x']);

        // Create a new payment
        PaymentFactory::createFromPayable($order, $this->paymentMethod, ['x' => 'N']);

        $order = $order->fresh(['currentPayment']);
        $this->assertEquals('N', $order->getCurrentPayment()->getExtraData()['x']);
    }

    /** @test */
    public function the_current_payment_is_being_loaded_with_the_minimal_number_of_queries()
    {
        $numbers = ['UHN7G59' => 3, 'UHN7G60' => 1, 'UHN7G61' => 2, 'UHN7G62' => 1, 'UHN7G63' => 2];
        foreach ($numbers as $number => $paymentsToCreate) {
            $order = Order::create([
                'number' => $number,
            ]);
            for ($i = 0; $i <= $paymentsToCreate; $i++) {
                PaymentFactory::createFromPayable($order, $this->paymentMethod);
            }
        }

        DB::enableQueryLog();
        Order::withCurrentPayment()->get();
        $this->assertCount(2, DB::getQueryLog());
        DB::flushQueryLog();
        DB::disableQueryLog();
    }

    /** @test */
    public function the_latest_payment_can_be_retrieved_via_the_getter_method_if_the_dynamic_relationship_is_not_loaded()
    {
        /** @var Order $order */
        $order = Order::create([
            'number' => 'KM9H81AD',
        ]);

        PaymentFactory::createFromPayable($order, $this->paymentMethod, ['mark' => '001']);
        PaymentFactory::createFromPayable($order, $this->paymentMethod, ['mark' => '002']);

        DB::enableQueryLog();
        $currentPayment = $order->getCurrentPayment();
        $this->assertInstanceOf(Payment::class, $currentPayment);
        // It calls the extra query since the dynamic relation isn't fetched
        $this->assertCount(1, DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertEquals('002', $currentPayment->getExtraData()['mark']);
    }

    /** @test */
    public function the_latest_payment_can_be_retrieved_via_the_getter_method_without_an_extra_query_if_the_dynamic_relationship_is_loaded()
    {
        /** @var Order $order */
        $order = Order::create([
            'number' => 'NOQUERY',
        ]);

        PaymentFactory::createFromPayable($order, $this->paymentMethod, ['mark' => '005']);
        PaymentFactory::createFromPayable($order, $this->paymentMethod, ['mark' => '009']);
        PaymentFactory::createFromPayable($order, $this->paymentMethod, ['mark' => '011']);

        $order = Order::withCurrentPayment()->where('number', 'NOQUERY')->first();

        DB::enableQueryLog();
        $currentPayment = $order->getCurrentPayment();
        $this->assertInstanceOf(Payment::class, $currentPayment);
        // It must call no query since the dynamic relation is already fetched
        $this->assertCount(0, DB::getQueryLog());
        DB::flushQueryLog();
        DB::disableQueryLog();

        $this->assertEquals('011', $currentPayment->getExtraData()['mark']);
    }
}
