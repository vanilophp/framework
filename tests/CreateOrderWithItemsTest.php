<?php
/**
 * Contains the CreateOrderWithItemsTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-11-28
 *
 */


namespace Vanilo\Order\Tests;


use Vanilo\Order\Models\Order;

class CreateOrderWithItemsTest extends TestCase
{
    /**
     * @test
     */
    public function order_with_a_single_item_can_be_created()
    {
        $order = Order::create([
            'number' => 'CXOIL41'
        ]);

        $order->items()->create([
            'product_type' => 'product',
            'product_id'   => 1,
            'name'         => 'Tesla charger',
            'quantity'     => 1,
            'price'        => 179.99
        ]);

        $this->assertCount(1, $order->items);

        $item = $order->items->first();

        $this->assertEquals('product', $item->product_type);
        $this->assertEquals(1, $item->product_id);
        $this->assertEquals('Tesla charger', $item->name);
        $this->assertEquals(1, $item->quantity);
        $this->assertEquals(179.99, $item->price);

        // Refetch from DB and repeat
        $order = Order::find(1);
        $this->assertCount(1, $order->items);

        $item = $order->items->first();

        $this->assertEquals('product', $item->product_type);
        $this->assertEquals(1, $item->product_id);
        $this->assertEquals('Tesla charger', $item->name);
        $this->assertEquals(1, $item->quantity);
        $this->assertEquals(179.99, $item->price);
    }

    /**
     * @test
     */
    public function order_with_multiple_items_can_be_created()
    {
        $order = Order::create([
            'number' => 'X5C1HW'
        ]);

        $order->items()->createMany([
            [
                'product_type' => 'product',
                'product_id'   => 1,
                'name'         => 'Tesla charger',
                'quantity'     => 1,
                'price'        => 179.99
            ],
            [
                'product_type' => 'product',
                'product_id'   => 2,
                'name'         => 'Prius charger',
                'quantity'     => 1,
                'price'        => 199.99
            ]
        ]);

        $this->assertCount(2, $order->items);

        $tesla = $order->items->first();
        $prius = $order->items->last();

        $this->assertEquals('Tesla charger', $tesla->name);
        $this->assertEquals('product', $tesla->product_type);
        $this->assertEquals(1, $tesla->product_id);
        $this->assertEquals(1, $tesla->quantity);
        $this->assertEquals(179.99, $tesla->price);

        $this->assertEquals('Prius charger', $prius->name);
        $this->assertEquals('product', $prius->product_type);
        $this->assertEquals(2, $prius->product_id);
        $this->assertEquals(1, $prius->quantity);
        $this->assertEquals(199.99, $prius->price);
    }
}
