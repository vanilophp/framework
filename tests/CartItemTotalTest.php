<?php
/**
 * Contains the CartItemTotalTest class.
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

class CartItemTotalTest extends TestCase
{
    /** @var  Product */
    protected $greenBook;

    /** @var  Product */
    protected $blueBook;

    protected function setUp(): void
    {
        parent::setUp();

        $this->greenBook = Product::create([
            'name'  => 'Green Book',
            'price' => 12.50
        ]);

        $this->blueBook = Product::create([
            'name'  => 'Blue Book',
            'price' => 17.50 // blue is more expensive because of Monty Python
        ]);
    }

    /**
     * @test
     */
    public function the_item_total_method_returns_price_times_quantity()
    {
        $item1 = Cart::addItem($this->blueBook);
        $item2 = Cart::addItem($this->greenBook, 3);

        $this->assertEquals(
            $this->blueBook->getPrice(),
            $item1->total()
        );

        $this->assertEquals(
            3 * $this->greenBook->getPrice(),
            $item2->total()
        );

        $item1 = Cart::addItem($this->blueBook);

        $this->assertEquals(
            2 * $this->blueBook->getPrice(),
            $item1->total()
        );

        $item2 = Cart::addItem($this->greenBook, 2);

        $this->assertEquals(
            5 * $this->greenBook->getPrice(),
            $item2->total()
        );
    }

    /**
     * @test
     */
    public function the_item_total_can_be_accessed_via_the_total_property_as_well()
    {
        $item = Cart::addItem($this->blueBook, 2);

        $this->assertEquals(
            2 * $this->blueBook->getPrice(),
            $item->total
        );
    }
}
