<?php
/**
 * Contains the CartCustomItemAttributes Test class.
 *
 * @copyright   Copyright (c) 2018 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2018-03-15
 *
 */

namespace Vanilo\Cart\Tests;

use Illuminate\Database\Schema\Blueprint;
use Vanilo\Cart\Exceptions\InvalidCartConfigurationException;
use Vanilo\Cart\Facades\Cart;
use Vanilo\Cart\Tests\Dummies\Product;
use Vanilo\Cart\Tests\Dummies\SizedProduct;

class CartCustomItemAttributesTest extends TestCase
{
    /**
     * @test
     */
    public function custom_product_attributes_specified_in_config_are_copied_to_cart_item_on_adding_product_to_cart()
    {
        config([
            'vanilo.cart.extra_product_attributes' => [
                'width', 'height', 'depth'
            ]
        ]);

        $product = SizedProduct::create([
            'name'   => 'Kids Backpack in Strawberry Design',
            'price'  => 14.99,
            'width'  => 25,
            'height' => 29,
            'depth'  => 15
        ]);

        $item = Cart::addItem($product);

        $this->assertEquals(25, $item->width);
        $this->assertEquals(29, $item->height);
        $this->assertEquals(15, $item->depth);
    }

    /**
     * @test
     */
    public function custom_cart_item_attributes_can_be_passed_and_are_stored_when_adding_a_product_to_cart()
    {
        $product = Product::create([
            'name'   => 'Kids Backpack Dinosaur',
            'price'  => 12.99
        ]);

        $item = Cart::addItem($product, 1, ['attributes' => [
            'width'  => 22,
            'height' => 27,
            'depth'  => 9
        ]]);

        $this->assertEquals(22, $item->width);
        $this->assertEquals(27, $item->height);
        $this->assertEquals(9, $item->depth);
    }

    /**
     * @test
     */
    public function setting_nonstring_extra_product_attributes_with_configuration_throws_an_exception()
    {
        $this->expectException(InvalidCartConfigurationException::class);

        config(['vanilo.cart.extra_product_attributes' => [1]]);

        $product = Product::create([
            'name'   => 'Kids Backpack Dinosaur',
            'price'  => 12.99
        ]);

        Cart::addItem($product);
    }

    /**
     * @test
     */
    public function setting_nonarray_as_extra_product_attributes_configuration_throws_an_exception()
    {
        $this->expectException(InvalidCartConfigurationException::class);

        config(['vanilo.cart.extra_product_attributes' => 'width']);

        $product = Product::create([
            'name'   => 'Kids Backpack Dinosaur',
            'price'  => 12.99
        ]);

        Cart::addItem($product);
    }

    /**
     * @test
     */
    public function explicitly_passed_extra_attributes_override_configured_extra_attributes_from_model()
    {
        config([
            'vanilo.cart.extra_product_attributes' => [
                'width', 'height', 'depth'
            ]
        ]);

        $product = SizedProduct::create([
            'name'   => 'Kids Backpack in Strawberry Design',
            'price'  => 14.99,
            'width'  => 25,
            'height' => 29,
            'depth'  => 15
        ]);

        $item = Cart::addItem($product, 1, ['attributes' => ['width' => 27, 'depth' => 14]]);

        $this->assertEquals(27, $item->width);
        $this->assertEquals(29, $item->height);
        $this->assertEquals(14, $item->depth);
    }

    /**
     * Set up the database.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    protected function setUpDatabase($app)
    {
        parent::setUpDatabase($app);

        $app['db']->connection()->getSchemaBuilder()->create('sized_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('sku')->nullable();
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->integer('width');
            $table->integer('height');
            $table->integer('depth');
            $table->timestamps();
        });

        $app['db']->connection()->getSchemaBuilder()->table('cart_items', function (Blueprint $table) {
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->integer('depth')->nullable();
        });
    }
}
