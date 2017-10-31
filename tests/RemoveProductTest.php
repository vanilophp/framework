<?php
/**
 * Contains the RemoveProduct Test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-30
 *
 */


namespace Vanilo\Cart\Tests;

use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;

class RemoveProductTest extends TestCase
{
    /** @var  Product */
    protected $product3;

    /** @var  Product */
    protected $product4;

    /**
     * @test
     */
    public function a_single_item_can_be_removed()
    {
        Cart::addItem($this->product4);

        $this->assertEquals(1, Cart::itemCount());

        Cart::removeProduct($this->product4);

        $this->assertEquals(0, Cart::itemCount());
        $this->assertCount(0, Cart::model()->items);
        $this->assertTrue(Cart::isEmpty());
    }

    /**
     * @test
     */
    public function removing_an_item_leaves_other_items_intact()
    {
        Cart::addItem($this->product3);
        Cart::addItem($this->product4);

        $this->assertEquals(2, Cart::itemCount());

        Cart::removeProduct($this->product3);

        $this->assertEquals(1, Cart::itemCount());
        $this->assertCount(1, Cart::model()->items);
        $this->assertEquals($this->product4->id, Cart::model()->items->first()->product_id);
        $this->assertTrue(Cart::isNotEmpty());
    }

    public function setUp()
    {
        parent::setUp();

        $this->product3 = Product::create([
            'name'  => 'Saint George Pizza',
            'price' => 9.79]);

        $this->product4 = Product::create([
            'name'  => 'Pizza With Goose Liver',
            'price' => 8.89
        ]);
    }
}
