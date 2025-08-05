<?php

declare(strict_types=1);

/**
 * Contains the ProxyTest class.
 *
 * @copyright   Copyright (c) 2020 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2020-12-08
 *
 */

namespace Vanilo\Payment\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Payment\Models\PaymentMethod;
use Vanilo\Payment\Models\PaymentMethodProxy;

class ProxyTest extends TestCase
{
    #[Test] public function the_payment_method_proxy_resolves_to_the_supplied_model()
    {
        $this->assertEquals(PaymentMethod::class, PaymentMethodProxy::modelClass());
    }
}
