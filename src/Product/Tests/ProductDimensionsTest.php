<?php

declare(strict_types=1);

/**
 * Contains the ProductDimensionsTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-28
 *
 */

namespace Vanilo\Product\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Contracts\Dimension;
use Vanilo\Product\Models\Product;

class ProductDimensionsTest extends TestCase
{
    #[Test] public function the_weight_field_is_null_by_default()
    {
        $product = Product::create(['sku' => 'SHOE123', 'name' => 'Shoe 123'])->fresh();

        $this->assertNull($product->weight);
    }

    #[Test] public function the_weight_field_is_a_float_if_it_has_a_value()
    {
        $product = Product::create(['sku' => 'SHOE124', 'name' => 'Shoe 124', 'weight' => 2.56])->fresh();

        $this->assertIsFloat($product->weight);
        $this->assertEquals(2.56, $product->weight);
    }

    #[Test] public function the_height_field_is_null_by_default()
    {
        $product = Product::create(['sku' => 'SHOE125', 'name' => 'Shoe 125'])->fresh();

        $this->assertNull($product->height);
    }

    #[Test] public function the_height_field_is_a_float_if_it_has_a_value()
    {
        $product = Product::create(['sku' => 'SHOE126', 'name' => 'Shoe 126', 'height' => 3.14])->fresh();

        $this->assertIsFloat($product->height);
        $this->assertEquals(3.14, $product->height);
    }

    #[Test] public function the_width_field_is_null_by_default()
    {
        $product = Product::create(['sku' => 'SHOE127', 'name' => 'Shoe 127'])->fresh();

        $this->assertNull($product->width);
    }

    #[Test] public function the_width_field_is_a_float_if_it_has_a_value()
    {
        $product = Product::create(['sku' => 'SHOE128', 'name' => 'Shoe 128', 'width' => 0.65])->fresh();

        $this->assertIsFloat($product->width);
        $this->assertEquals(0.65, $product->width);
    }

    #[Test] public function the_length_field_is_null_by_default()
    {
        $product = Product::create(['sku' => 'SHOE129', 'name' => 'Shoe 129'])->fresh();

        $this->assertNull($product->length);
    }

    #[Test] public function the_length_field_is_a_float_if_it_has_a_value()
    {
        $product = Product::create(['sku' => 'SHOE12A', 'name' => 'Shoe 12A', 'length' => 1.2735])->fresh();

        $this->assertIsFloat($product->length);
        $this->assertEquals(1.2735, $product->length);
    }

    #[Test] public function it_has_no_dimensions_if_any_of_the_3_dimensions_is_null()
    {
        $product = new Product();

        $this->assertFalse($product->hasDimensions());
        $this->assertNull($product->dimension());

        $product->height = 1;
        $this->assertFalse($product->hasDimensions());
        $this->assertNull($product->dimension());

        $product->width = 2;
        $this->assertFalse($product->hasDimensions());
        $this->assertNull($product->dimension());

        $product->length = 1.3;
        $this->assertTrue($product->hasDimensions());
    }

    #[Test] public function it_returns_a_dimension_object_containing_all_the_related_fields()
    {
        $product = new Product();

        $this->assertNull($product->dimension());

        $product->height = 2.7;
        $this->assertNull($product->dimension());

        $product->width = 2.1;
        $this->assertNull($product->dimension());

        $product->length = 1.83;
        $this->assertInstanceOf(Dimension::class, $product->dimension());
    }
}
