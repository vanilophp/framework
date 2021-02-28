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

use Vanilo\Framework\Models\Order;
use Vanilo\Framework\Models\PaymentMethod;
use Vanilo\Payment\Factories\PaymentFactory;

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
}
