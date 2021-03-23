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

namespace Vanilo\Framework\Tests;

use Illuminate\Support\Facades\DB;
use Vanilo\Framework\Models\Order;
use Vanilo\Framework\Models\PaymentMethod;
use Vanilo\Payment\Factories\PaymentFactory;
use Vanilo\Payment\Models\Payment;

class PaymentsTest extends TestCase
{
    /** @test */
    public function an_order_can_have_multiple_payments()
    {
        $paymentMethod = PaymentMethod::create([
            'name' => 'Wire Transfer',
            'gateway' => 'null',
        ]);

        $order = Order::create([
            'number' => '5732952',
        ]);

        $payment1 = PaymentFactory::createFromPayable($order, $paymentMethod);
        $payment2 = PaymentFactory::createFromPayable($order, $paymentMethod);

        $this->assertCount(2, $order->payments);
    }

    /** @test */
    public function the_current_payment_on_orders_returns_the_latest_payment_associated()
    {
        $paymentMethod = PaymentMethod::create([
            'name' => 'Wire Transfer',
            'gateway' => 'null',
        ]);

        /** @var Order $order */
        $order = Order::create([
            'number' => '5732952',
        ]);

        PaymentFactory::createFromPayable($order, $paymentMethod, ['x' => 'a']);
        PaymentFactory::createFromPayable($order, $paymentMethod, ['x' => 'c']);
        PaymentFactory::createFromPayable($order, $paymentMethod, ['x' => 'b']);

        $this->assertInstanceOf(Payment::class, $order->currentPayment);
        $this->assertEquals('b', $order->currentPayment->getExtraData()['x']);

        // Create a new payment
        PaymentFactory::createFromPayable($order, $paymentMethod, ['x' => 'N']);

        $order = $order->fresh(['currentPayment']);
        $this->assertEquals('N', $order->currentPayment->getExtraData()['x']);
    }

    /** @test */
    public function the_current_payment_can_be_eager_loaded()
    {
        $paymentMethod = PaymentMethod::create([
            'name' => 'Wire Transfer',
            'gateway' => 'null',
        ]);

        $order = Order::create([
            'number' => 'UHN7G59',
        ]);

        PaymentFactory::createFromPayable($order, $paymentMethod, ['index' => 10]);
        PaymentFactory::createFromPayable($order, $paymentMethod, ['index' => 20]);

        DB::enableQueryLog();
        $order = Order::with('currentPayment')->where('number', 'UHN7G59')->first();
        // @todo it shall do a join, not 2 separate queries
        //dd(DB::getQueryLog());
        $this->assertCount(1, DB::getQueryLog());
        DB::disableQueryLog();
    }
}
