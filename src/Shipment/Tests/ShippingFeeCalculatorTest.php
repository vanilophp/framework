<?php

declare(strict_types=1);

/**
 * Contains the ShippingFeeCalculatorTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-06
 *
 */

namespace Vanilo\Shipment\Tests;

use Vanilo\Shipment\Calculators\NullShippingFeeCalculator;
use Vanilo\Shipment\Models\ShippingFee;
use Vanilo\Shipment\Models\ShippingMethod;

class ShippingFeeCalculatorTest extends TestCase
{
    /** @test */
    public function when_having_null_as_calculator_a_null_calculator_object_is_returned()
    {
        $method = ShippingMethod::create(['name' => 'DPD']);

        $this->assertInstanceOf(NullShippingFeeCalculator::class, $method->getCalculator());
    }

    /** @test */
    public function the_null_calculator_returns_no_adjuster()
    {
        $method = ShippingMethod::create(['name' => 'Seur']);

        $this->assertNull($method->getCalculator()->getAdjuster());
    }

    /** @test */
    public function the_null_calculator_returns_zero_amount_fee()
    {
        $method = ShippingMethod::create(['name' => 'Seur']);

        $fee = $method->getCalculator()->calculate();
        $this->assertInstanceOf(ShippingFee::class, $fee);
        $this->assertEquals(0, $fee->amount()->getValue());
    }

    /** @test */
    public function the_detailed_amount_can_be_directly_obtained_by_calling_the_estimate_method_of_the_shipping_method()
    {
        $method = ShippingMethod::create(['name' => 'Seur']);

        $fee = $method->estimate();
        $this->assertInstanceOf(ShippingFee::class, $fee);
        $this->assertEquals(0, $fee->amount()->getValue());
    }
}
