<?php
/**
 * Contains the CartTotalTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-31
 *
 */

namespace Vanilo\Cart\Tests;

use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;

class CartTotalTest extends TestCase
{
    /** @var  Product */
    protected $blackCat;

    /** @var  Product */
    protected $whiteCat;

    /**
     * @test
     */
    public function a_non_existent_cart_has_a_zero_total()
    {
        $this->assertEquals(0, Cart::total());
    }

    /**
     * @test
     */
    public function cart_total_returns_the_total_of_the_items()
    {
        $item1 = Cart::addItem($this->whiteCat, 2);
        $item2 = Cart::addItem($this->blackCat);

        $manualCheckSum = $this->blackCat->getPrice()
            +
            2 * $this->whiteCat->getPrice()
        ;

        $this->assertEquals($item1->total() + $item2->total(), Cart::total());

        $this->assertEquals($manualCheckSum, Cart::total());
    }

    /**
     * @test
     */
    public function cart_total_remains_correct_after_removing_items()
    {
        Cart::addItem($this->whiteCat);
        Cart::addItem($this->blackCat);

        $this->assertEquals(
            $this->whiteCat->getPrice() + $this->blackCat->getPrice(),
            Cart::total()
        );

        Cart::removeProduct($this->blackCat);

        $this->assertEquals($this->whiteCat->getPrice(), Cart::total());
    }

    /**
     * @test
     */
    public function an_empty_but_existent_cart_has_a_zero_total()
    {
        Cart::addItem($this->blackCat);
        Cart::removeProduct($this->blackCat);

        $this->assertEquals(0, Cart::total());
    }

    /**
     * @test
     */
    public function a_cleared_cart_has_zero_total()
    {
        Cart::addItem($this->blackCat);
        Cart::clear();

        $this->assertEquals(0, Cart::total());
    }

    public function setUp()
    {
        parent::setUp();

        $this->blackCat = Product::create([
            'name'  => 'Black Cat',
            'price' => 85
        ]);

        $this->whiteCat = Product::create([
            'name'  => 'White Cat',
            'price' => 80
        ]);
    }
}
