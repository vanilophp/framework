<?php

declare(strict_types=1);

/**
 * Contains the PaymentMethodDependentShippingFeeTest class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-11-11
 *
 */

namespace Vanilo\Foundation\Tests;


use Vanilo\Adjustments\Models\AdjustmentType;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Shipping\PaymentDependentShippingFeeCalculator;
use Vanilo\Shipment\Models\ShippingFee;
use Vanilo\Shipment\Models\ShippingMethod;

class PaymentMethodDependentShippingFeeTest extends TestCase
{
    /** @test */
    public function it_calculates_the_default_price_when_there_is_no_shipping_method_set()
    {
        $product = factory(Product::class)->create(['price' => 23]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());

        $calculator = new PaymentDependentShippingFeeCalculator();
        $result = $calculator->calculate(Checkout::getFacadeRoot(), ['prices' => ['default' => 10]]);
        $this->assertInstanceOf(ShippingFee::class, $result);
        $this->assertTrue($result->isEstimate());
        $this->assertEquals(10, $result->amount()->getValue());
    }

    /** @test */
    public function it_calculates_the_default_price_when_the_shipping_method_is_not_in_the_configuration()
    {
        $product = factory(Product::class)->create(['price' => 23]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        Checkout::setPaymentMethodId(4);

        $calculator = new PaymentDependentShippingFeeCalculator();
        $result = $calculator->calculate(Checkout::getFacadeRoot(), ['prices' => ['default' => 10, '1' => 5]]);
        $this->assertInstanceOf(ShippingFee::class, $result);
        $this->assertFalse($result->isEstimate());
        $this->assertEquals(10, $result->amount()->getValue());
    }

    /** @test */
    public function it_calculates_the_configured_price_when_the_shipping_method_is_present_in_the_configuration()
    {
        $product = factory(Product::class)->create(['price' => 23]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        Checkout::setPaymentMethodId('2');

        $calculator = new PaymentDependentShippingFeeCalculator();
        $result = $calculator->calculate(Checkout::getFacadeRoot(), ['prices' => ['default' => 10, '2' => 7.13]]);
        $this->assertInstanceOf(ShippingFee::class, $result);
        $this->assertFalse($result->isEstimate());
        $this->assertEquals(7.13, $result->amount()->getValue());
    }

    /** @test */
    public function it_calculates_the_default_price_when_the_shipping_method_has_no_explicitly_given_price()
    {
        $product = factory(Product::class)->create(['price' => 30]);
        $shippingMethod1 = ShippingMethod::create([
            'name' => 'Slow',
            'calculator' => PaymentDependentShippingFeeCalculator::ID,
            'configuration' => ['prices'=> ['default' => 5.99]],
        ]);
        $shippingMethod2 = ShippingMethod::create([
            'name' => 'Fast',
            'calculator' => PaymentDependentShippingFeeCalculator::ID,
            'configuration' => ['prices'=> ['default' => 8.99]],
        ]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        Checkout::setShippingMethodId($shippingMethod1->id);

        $calculator = new PaymentDependentShippingFeeCalculator();
        $result = $calculator->calculate(Checkout::getFacadeRoot(), $shippingMethod1->configuration());
        $this->assertInstanceOf(ShippingFee::class, $result);
        $this->assertTrue($result->isEstimate());
        $this->assertEquals(5.99, $result->amount()->getValue());
    }

    /** @test */
    public function it_creates_an_adjustment_with_the_default_price_when_there_is_no_payment_method_set()
    {
        $product = factory(Product::class)->create(['price' => 79.99]);
        $shippingMethod1 = ShippingMethod::create([
            'name' => 'Delivery Standard #1',
            'calculator' => PaymentDependentShippingFeeCalculator::ID,
            'configuration' => ['prices'=> ['default' => 5.99]],
        ]);
        $shippingMethod2 = ShippingMethod::create([
            'name' => 'Delivery Rapid #2',
            'calculator' => PaymentDependentShippingFeeCalculator::ID,
            'configuration' => ['prices'=> ['default' => 8.99]],
        ]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        Checkout::setShippingMethodId($shippingMethod1->id);

        $shippingAdjustments = Cart::adjustments()->byType(AdjustmentType::SHIPPING());
        $this->assertCount(1, $shippingAdjustments);
        $shippingAdjustment = $shippingAdjustments->first();
        $this->assertEquals(5.99, $shippingAdjustment->getAmount());

        Checkout::setShippingMethodId($shippingMethod2->id);
        $shippingAdjustments = Cart::adjustments()->byType(AdjustmentType::SHIPPING());
        $this->assertCount(1, $shippingAdjustments);
        $shippingAdjustment = $shippingAdjustments->first();
        $this->assertEquals(8.99, $shippingAdjustment->getAmount());

        Checkout::setShippingMethodId($shippingMethod1->id);
        $shippingAdjustments = Cart::adjustments()->byType(AdjustmentType::SHIPPING());
        $this->assertCount(1, $shippingAdjustments);
        $shippingAdjustment = $shippingAdjustments->first();
        $this->assertEquals(5.99, $shippingAdjustment->getAmount());
    }

    /** @test */
    public function it_creates_an_adjustment_with_the_default_price_when_the_payment_method_has_no_explicit_price_in_the_config()
    {
        $product = factory(Product::class)->create(['price' => 50]);
        $shippingMethod1 = ShippingMethod::create([
            'name' => 'Delivery X',
            'calculator' => PaymentDependentShippingFeeCalculator::ID,
            'configuration' => ['prices'=> ['default' => 5.99, '1' => 8]],
        ]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        Checkout::setShippingMethodId($shippingMethod1->id);
        Checkout::setPaymentMethodId(2);

        $shippingAdjustments = Cart::adjustments()->byType(AdjustmentType::SHIPPING());
        $this->assertCount(1, $shippingAdjustments);
        $shippingAdjustment = $shippingAdjustments->first();
        $this->assertEquals(5.99, $shippingAdjustment->getAmount());
    }

    /** @test */
    public function it_changes_the_price_of_the_shipping_adjustment_when_the_payment_method_changes()
    {
        $product = factory(Product::class)->create(['price' => 64]);
        $shippingMethod1 = ShippingMethod::create([
            'name' => 'Delivery Y',
            'calculator' => PaymentDependentShippingFeeCalculator::ID,
            'configuration' => ['prices'=> ['default' => 20, '3' => 15]],
        ]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        Checkout::setShippingMethodId($shippingMethod1->id);

        $shippingAdjustments = Cart::adjustments()->byType(AdjustmentType::SHIPPING());
        $this->assertCount(1, $shippingAdjustments);
        $shippingAdjustment = $shippingAdjustments->first();
        $this->assertEquals(20, $shippingAdjustment->getAmount());

        Checkout::setPaymentMethodId(3);

        $shippingAdjustments = Cart::adjustments()->byType(AdjustmentType::SHIPPING());
        $this->assertCount(1, $shippingAdjustments);
        $shippingAdjustment = $shippingAdjustments->first();
        $this->assertEquals(15, $shippingAdjustment->getAmount());
    }
}
