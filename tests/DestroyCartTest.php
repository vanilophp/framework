<?php
/**
 * Contains the DestroyCart Test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-30
 *
 */


namespace Vanilo\Cart\Tests;

use Vanilo\Cart\Contracts\Cart as CartContract;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;

class DestroyCartTest extends TestCase
{
    /** @var  Product */
    protected $product7;

    /** @var  Product */
    protected $product8;

    /**
     * @test
     */
    public function cart_will_be_empty_if_you_destroy_the_cart()
    {
        Cart::addItem($this->product7);

        $this->assertTrue(Cart::isNotEmpty());

        Cart::destroy();
        $this->assertTrue(Cart::isEmpty());
    }

    /**
     * @test
     */
    public function cart_becomes_nonexistent_after_destroy()
    {
        Cart::addItem($this->product7);
        Cart::addItem($this->product8);

        $this->assertTrue(Cart::exists());

        Cart::destroy();
        $this->assertTrue(Cart::doesNotExist());
    }

    /**
     * @test
     */
    public function cart_does_not_have_a_model_after_destroy()
    {
        Cart::addItem($this->product8);

        $this->assertInstanceOf(CartContract::class, Cart::model());

        Cart::destroy();
        $this->assertNull(Cart::model());
    }

    public function setUp()
    {
        parent::setUp();

        $this->product7 = Product::create([
            'name'  => 'Yellow Laptop',
            'price' => 899
        ]);

        $this->product8 = Product::create([
            'name'  => 'Orange Laptop',
            'price' => 949
        ]);
    }
}
