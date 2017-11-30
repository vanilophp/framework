<?php
/**
 * Contains the OrderItemProductTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-30
 *
 */


namespace Vanilo\Order\Tests;


use Vanilo\Contracts\Buyable;
use Vanilo\Order\Models\Order;
use Vanilo\Order\Tests\Dummies\Product;

class OrderItemProductTest extends TestCase
{
    /** @var Product */
    protected $theMoonRing;

    public function setUp()
    {
        parent::setUp();

        $this->theMoonRing = Product::create([
            'name'  => 'The Moon Ring',
            'sku'   => 'B01KR3SAIS',
            'price' => 17.95
        ]);
    }


    /**
     * @test
     */
    public function product_can_be_added_to_order_item()
    {
        $order = Order::create([
            'number' => 'FVJH8'
        ]);

        $order->items()->create([
            'product_type' => 'product',
            'product_id'   => $this->theMoonRing->getId(),
            'quantity'     => 1,
            'name'         => $this->theMoonRing->getName(),
            'price'        => $this->theMoonRing->getPrice()
        ]);

        $item = $order->items->first();
        $product = $item->product;
        $this->assertInstanceOf(Buyable::class, $product);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($this->theMoonRing->getName(), $product->getName());

        // Re-fetch from DB
        $order = $order->fresh();

        // And repeat the test
        $item = $order->items->first();
        $product = $item->product;
        $this->assertInstanceOf(Buyable::class, $product);
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($this->theMoonRing->getName(), $product->getName());
    }
}
