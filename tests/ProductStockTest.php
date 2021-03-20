<?php

declare(strict_types=1);

namespace Vanilo\Product\Tests;

use Vanilo\Product\Models\Product;

class ProductStockTest extends TestCase
{
    /**
     * @test
     */
    public function field_value_can_be_specified()
    {
        $product = Product::create([
            'name' => 'Dell Latitude E7240 Laptop',
            'sku' => 'DLL-74237',
            'stock' => 123.45
        ]);

        $this->assertEquals(123.45, $product->stock);
    }

    /**
     * @test
     */
    public function field_value_returns_a_numeric_value()
    {
        $createdProduct = Product::create([
            'name' => 'Dell Latitude E7240 Laptop',
            'sku' => 'DLL-74237',
            'stock' => 123.45
        ]);

        $product = Product::find($createdProduct->id);

        $this->assertTrue(\is_numeric($product->stock));
    }

    /**
     * @test
     */
    public function field_value_defaults_to_zero()
    {
        $product = Product::create([
            'name' => 'Dell Latitude E7240 Laptop',
            'sku' => 'DLL-74237'
        ]);

        $this->assertEquals(0, $product->stock);
    }

    /**
     * @test
     */
    public function isOnStock_returns_true_if_the_stock_is_greater_than_zero()
    {
        $product = Product::create([
            'name' => 'Dell Latitude E7240 Laptop',
            'sku' => 'DLL-74237',
            'stock' => 123.45
        ]);

        $this->assertTrue($product->isOnStock());
    }

    /**
     * @test
     */
    public function isOnStock_returns_false_if_the_stock_is_equal_to_zero()
    {
        $product = Product::create([
            'name' => 'Dell Latitude E7240 Laptop',
            'sku' => 'DLL-74237',
            'stock' => 0
        ]);

        $this->assertFalse($product->isOnStock());
    }

    /**
     * @test
     */
    public function isOnStock_returns_false_if_the_stock_is_less_than_zero()
    {
        $product = Product::create([
            'name' => 'Dell Latitude E7240 Laptop',
            'sku' => 'DLL-74237',
            'stock' => -123.45
        ]);

        $this->assertFalse($product->isOnStock());
    }
}
