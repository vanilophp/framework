<?php

declare(strict_types=1);

/**
 * Contains the ExtendedOrderFactoryTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-05
 *
 */

namespace Vanilo\Foundation\Tests;

use Konekt\Address\Models\Country;
use PHPUnit\Framework\Attributes\Test;
use Vanilo\Adjustments\Models\AdjustmentType;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Checkout\Facades\Checkout;
use Vanilo\Foundation\Factories\OrderFactory;
use Vanilo\Foundation\Models\Address;
use Vanilo\Foundation\Models\Order;
use Vanilo\Foundation\Models\Product;
use Vanilo\Foundation\Shipping\FlatFeeCalculator;
use Vanilo\Order\Generators\NanoIdGenerator;
use Vanilo\Order\Models\Billpayer;
use Vanilo\Shipment\Models\ShippingMethod;

class ExtendedOrderFactoryTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Country::firstOrCreate(
            ['id' => 'RU'],
            ['name' => 'Russia', 'phonecode' => 7, 'is_eu_member' => false],
        );
    }

    /** @test */
    public function it_copies_the_shipping_adjustment_over_from_the_cart_to_the_order()
    {
        $product = factory(Product::class)->create(['price' => 25]);
        $shippingMethod = ShippingMethod::create([
            'name' => 'Delivery to your Door',
            'calculator' => FlatFeeCalculator::ID,
            'configuration' => ['cost' => 5],
        ]);

        Cart::addItem($product);
        Checkout::setCart(Cart::getFacadeRoot());
        $this->completeCheckout($shippingMethod->id);

        $this->assertCount(1, Cart::adjustments()->byType(AdjustmentType::SHIPPING()));
        $this->assertEquals(30, Checkout::total());

        $factory = new OrderFactory(new NanoIdGenerator());
        $order = $factory->createFromCheckout(Checkout::getFacadeRoot());

        $this->assertInstanceOf(Order::class, $order);
        $adjustments = $order->adjustments();
        $this->assertCount(1, $adjustments);
        $this->assertEquals(AdjustmentType::SHIPPING(), $adjustments->first()->getType());
        $this->assertEquals(30, $order->total());
        $this->assertEquals(25, $order->itemsTotal());
    }

    #[Test] public function it_maps_the_parent_relationships_correctly()
    {
        $product = factory(Product::class)->create(['price' => 25]);
        $product2 = factory(Product::class)->create(['price' => 50]);

        $shippingMethod = ShippingMethod::create([
            'name' => 'Delivery to your Door',
            'calculator' => FlatFeeCalculator::ID,
            'configuration' => ['cost' => 5],
        ]);

        $mainItem = Cart::addItem($product);
        $subItem = Cart::addSubItem($mainItem, $product2);

        Checkout::setCart(Cart::getFacadeRoot());
        $this->completeCheckout($shippingMethod->id);

        $factory = new OrderFactory(new NanoIdGenerator());
        $order = $factory->createFromCheckout(Checkout::getFacadeRoot());

        $item1 = $order->getItems()[0];
        $item2 = $order->getItems()[1];
        $this->assertEquals($item1->id, $item2->parent_id);
    }

    private function completeCheckout(int $useShippingMethodId): void
    {
        $data['billpayer'] = factory(Billpayer::class)->make()->toArray();
        $data['billpayer']['address'] = factory(Address::class)->make(['country_id' => 'RU'])->toArray();
        $data['ship_to_billing_address'] = true;
        $data['shipping_method_id'] = $useShippingMethodId;

        Checkout::update($data);
    }
}
