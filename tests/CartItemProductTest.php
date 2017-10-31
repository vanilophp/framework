<?php
/**
 * Contains the CartItemProduct Test class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-31
 *
 */


namespace Vanilo\Cart\Tests;


use Illuminate\Database\Eloquent\Relations\Relation;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Course;
use Vanilo\Cart\Tests\Dummies\Product;

class CartItemProductTest extends TestCase
{
    /** @var  Product */
    protected $alaskaSnow;

    /** @var  Course */
    protected $reactForBeginners;

    /**
     * @test
     */
    public function the_cart_item_returns_the_associated_product_with_a_custom_morph_type_name()
    {
        Cart::addItem($this->alaskaSnow);

        $product = Cart::model()->items->first()->product;

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals($this->alaskaSnow->id, $product->id);
    }

    /**
     * @test
     */
    public function the_cart_item_returns_the_associated_product_with_fqcn_as_morph_type_name()
    {
        Cart::addItem($this->reactForBeginners);

        $course = Cart::model()->items->first()->product;

        $this->assertInstanceOf(Course::class, $course);
        $this->assertEquals($this->reactForBeginners->id, $course->id);
    }

    public function setUp()
    {
        parent::setUp();

        // The cart module is unaware of any actual Buyables,
        // so the mapping gets defined here. Any consumers
        // of this module need to add their mapping too
        Relation::morphMap([
            shorten(Product::class) => Product::class
        ]);

        $this->alaskaSnow = Product::create([
            'name'  => 'Alaska Snow 34oz',
            'price' => 9
        ]);

        $this->reactForBeginners = Course::create([
            'title' => 'React For Beginners',
            'price' => 89
        ]);
    }
}
