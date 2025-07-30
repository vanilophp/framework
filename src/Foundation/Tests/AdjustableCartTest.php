<?php

declare(strict_types=1);

/**
 * Contains the AdjustableCartTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-03-25
 *
 */

namespace Vanilo\Foundation\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Adjustments\Adjusters\SimpleDiscount;
use Vanilo\Adjustments\Adjusters\SimpleShippingFee;
use Vanilo\Adjustments\Contracts\Adjustable;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Foundation\Models\Product;

class AdjustableCartTest extends TestCase
{
    #[Test] public function the_cart_model_is_an_adjustable()
    {
        Cart::create();
        $this->assertInstanceOf(Adjustable::class, Cart::model());
    }

    #[Test] public function an_adjustment_can_be_added()
    {
        $product = Product::create(['sku' => 'ASD-123', 'name' => 'Something', 'price' => 83.99]);
        Cart::addItem($product);
        Cart::adjustments()->create(new SimpleShippingFee(10));

        $this->assertEquals(93.99, Cart::total());
        $this->assertEquals(83.99, Cart::itemsTotal());
    }

    #[Test] public function adjustments_can_be_retrieved()
    {
        $product = Product::create(['sku' => 'ASD-123', 'name' => 'Some Shippable Thing', 'price' => 12.79]);
        Cart::addItem($product);
        $shippingFee = new SimpleShippingFee(7.99);
        $shippingFee->setTitle('FedEx Ground Shipping 1-3 days');
        Cart::adjustments()->create($shippingFee);

        $this->assertCount(1, Cart::getItems());
        $this->assertCount(1, Cart::adjustments());
        $this->assertEquals('FedEx Ground Shipping 1-3 days', Cart::adjustments()->first()->title);
    }

    #[Test] public function multiple_adjustments_can_be_added()
    {
        $product = Product::create(['sku' => 'QWE-456', 'name' => 'Something', 'price' => 50]);
        Cart::addItem($product);
        $shippingFee = new SimpleShippingFee(5);
        $shippingFee->setTitle('Shipping Fee');
        Cart::adjustments()->create($shippingFee);
        $discount = new SimpleDiscount(3);
        $discount->setTitle('Discount');
        Cart::adjustments()->create($discount);

        $this->assertCount(1, Cart::getItems());
        $this->assertCount(2, Cart::adjustments());
        $this->assertEquals('Shipping Fee', Cart::adjustments()->first()->title);
        $this->assertEquals('Discount', Cart::adjustments()->last()->title);
        $this->assertEquals(52, Cart::total());
    }
}
