<?php

declare(strict_types=1);

/**
 * Contains the DiscountableShippingFeeCalculationTest class.
 *
 * @copyright   Copyright (c) 2024 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2024-06-02
 *
 */

namespace Vanilo\Foundation\Tests;

use Vanilo\Adjustments\Contracts\AdjustmentCollection;
use Vanilo\Adjustments\Models\AdjustmentType;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Contracts\DetailedAmount;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Shipping\DiscountableShippingFeeCalculator;
use Vanilo\Shipment\Models\ShippingMethod;

class DiscountableShippingFeeCalculationTest extends TestCase
{
    /** @test */
    public function a_normal_shipping_fee_gets_calculated_when_there_is_no_discount_deal_and_the_free_shipping_threshold_is_not_exceeded()
    {
        $product = factory(Product::class)->create(['price' => 50]);
        $shippingMethod = ShippingMethod::create([
            'name' => 'Shippping',
            'calculator' => DiscountableShippingFeeCalculator::ID,
            'configuration' => ['cost' => 14.99],
        ]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        Checkout::setShippingMethodId($shippingMethod->id);

        /** @var AdjustmentCollection $shippingAdjustments */
        $shippingAdjustments = Cart::adjustments()->byType(AdjustmentType::SHIPPING());
        $this->assertCount(1, $shippingAdjustments);
        $shippingAdjustment = $shippingAdjustments->first();
        $this->assertEquals(14.99, $shippingAdjustment->getAmount());
        $this->assertTrue($shippingAdjustment->isCharge());
        $this->assertFalse($shippingAdjustment->isIncluded());
        $this->assertEquals(50, Cart::itemsTotal());
        $this->assertEquals(50 + 14.99, Cart::total());

        $shippingAmount = Checkout::getShippingAmount();
        $this->assertInstanceOf(DetailedAmount::class, $shippingAmount);
        $this->assertEquals(14.99, $shippingAmount->getValue());
    }

    /** @test */
    public function it_creates_a_shipping_adjustment_having_a_zero_sum_when_the_free_shipping_threshold_is_exceeded()
    {
        $product = factory(Product::class)->create(['price' => 40]);
        $shippingMethod = ShippingMethod::create([
            'name' => 'Discounted Fee Free',
            'calculator' => DiscountableShippingFeeCalculator::ID,
            'configuration' => ['cost' => 4.99, 'free_threshold' => 39.99],
        ]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        Checkout::setShippingMethodId($shippingMethod->id);

        /** @var AdjustmentCollection $shippingAdjustments */
        $shippingAdjustments = Cart::adjustments()->byType(AdjustmentType::SHIPPING());
        $this->assertCount(1, $shippingAdjustments);
        $shippingAdjustment = $shippingAdjustments->first();
        $this->assertEquals(0, $shippingAdjustment->getAmount());
        $this->assertEquals(40, Cart::itemsTotal());
        $this->assertEquals(40, Cart::total());

        $shippingDetails = Checkout::getShippingAmount();
        $this->assertEquals(0, $shippingDetails->getValue());
        $this->assertCount(2, $shippingDetails->getDetails());
    }

    /** @test */
    public function it_creates_a_shipping_adjustment_with_the_discounted_price_when_the_discounted_shipping_threshold_is_exceeded()
    {
        $product = factory(Product::class)->create(['price' => 100]);
        $shippingMethod = ShippingMethod::create([
            'name' => 'Discounted Fee',
            'calculator' => DiscountableShippingFeeCalculator::ID,
            'configuration' => [
                'cost' => 12.99,
                'free_threshold' => 150,
                'discounted_threshold' => 99.99,
                'discounted_cost' => 3.99,
            ],
        ]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        Checkout::setShippingMethodId($shippingMethod->id);

        /** @var AdjustmentCollection $shippingAdjustments */
        $shippingAdjustments = Cart::adjustments()->byType(AdjustmentType::SHIPPING());
        $this->assertCount(1, $shippingAdjustments);
        $shippingAdjustment = $shippingAdjustments->first();
        $this->assertEquals(3.99, $shippingAdjustment->getAmount());
        $this->assertEquals(100, Cart::itemsTotal());
        $this->assertEquals(103.99, Cart::total());

        $shippingDetails = Checkout::getShippingAmount();
        $this->assertEquals(3.99, $shippingDetails->getValue());
        $this->assertCount(2, $shippingDetails->getDetails());
    }
}
