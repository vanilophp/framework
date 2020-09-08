<?php
/**
 * Contains the PaymentMethodTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-04-26
 *
 */

namespace Vanilo\Payment\Tests;

use Vanilo\Payment\Models\PaymentMethod;

class PaymentMethodTest extends TestCase
{
    /** @test */
    public function it_can_be_created()
    {
        $method = PaymentMethod::create([
            'name'    => 'Credit Card',
            'gateway' => 'plastic'
        ]);

        $this->assertInstanceOf(PaymentMethod::class, $method);
    }
}
