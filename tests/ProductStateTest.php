<?php
/**
 * Contains the ProductStateTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */


namespace Vanilo\Product\Tests;

use Konekt\Enum\Enum;
use Vanilo\Product\Models\Product;
use Vanilo\Product\Models\ProductState;
use Vanilo\Product\Models\ProductStateProxy;

class ProductStateTest extends TestCase
{
    /**
     * @test
     */
    public function the_state_property_is_an_enum()
    {
        $product = Product::create([
            'name' => 'The Big Enums Book',
            'sku'  => 'BOOK-001'
        ]);

        $this->assertInstanceOf(Enum::class, $product->state);
        $this->assertInstanceOf(ProductStateProxy::enumClass(), $product->state);
    }

    /**
     * @test
     */
    public function the_state_property_is_the_default_when_unset()
    {
        $product = Product::create([
            'name' => 'The Medium Enums Book',
            'sku'  => 'BOOK-002'
        ]);

        $this->assertEquals(ProductStateProxy::defaultValue(), $product->state->value());
    }

    /**
     * @test
     */
    public function the_state_can_be_set_via_enum_object()
    {
        $product = Product::create([
            'name'  => 'The Small Enums Book',
            'sku'   => 'BOOK-003',
            'state' => ProductState::ACTIVE()
        ]);

        $this->assertTrue($product->state->equals(ProductState::ACTIVE()));
    }

    /**
     * @test
     */
    public function the_state_can_be_set_via_scalar()
    {
        $product = Product::create([
            'name'  => 'The Small Enums Book',
            'sku'   => 'BOOK-003',
            'state' => 'active'
        ]);

        $this->assertTrue($product->state->equals(ProductState::ACTIVE()));
    }

    /**
     * @test
     */
    public function the_products_is_active_method_returns_the_states_active_status()
    {
        $product = Product::create([
            'name' => 'The Tiny Enums Book',
            'sku'  => 'BOOK-004'
        ]);

        $defaultState = new ProductState();
        $this->assertTrue($product->state->equals($defaultState));

        // The assertion below was only added to make sure this case tests two consecutive
        // active/inactive states. Whenever the default state will change to active one
        // then modify this test by setting the second state enum to an inactive one
        $this->assertFalse($defaultState->isActive());

        $this->assertEquals($defaultState->isActive(), $product->isActive());

        $product->state = ProductState::ACTIVE();
        $product->save();

        $this->assertTrue($product->isActive());
    }

    /**
     * @test
     */
    public function active_status_can_be_returned_by_the_is_active_property_as_well()
    {
        $product = Product::create([
            'name'  => 'The Micro Enum Book',
            'sku'   => 'BOOK-005',
            'state' => ProductState::ACTIVE
        ]);

        $this->assertTrue($product->is_active);

        $product->state = ProductState::RETIRED;
        $product->save();

        $this->assertFalse($product->is_active);
    }
}
