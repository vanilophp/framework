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
    public function is_on_stock_returns_false_if_the_stock_is_less_than_zero()
    {
        $product = Product::create([
            'name' => 'Dell Latitude E7240 Laptop',
            'sku' => 'DLL-74237',
            'stock' => -123.45
        ]);

        $this->assertFalse($product->isOnStock());
    }

    /** @test */
    public function backorder_value_can_be_specified()
    {
        $product = Product::create([
            'name' => 'Semperit 165/70 R 14',
            'sku' => 'SEMPED-GRIP1',
            'backorder' => 16,
        ]);

        $this->assertEquals(16, $product->backorder);
    }

    /** @test */
    public function backorder_is_null_by_default()
    {
        $product = Product::create([
            'name' => 'Semperit 165/70 R 15',
            'sku' => 'SEMPED-GRIP2',
            'backorder' => null,
        ]);

        $this->assertNull($product->backorder);
    }

    /** @test */
    public function it_implements_the_stockable_interface()
    {
        $product = Product::create([
            'name' => 'Semperit 165/70 R 16',
            'sku' => 'SEMPED-GRIP3',
            'stock' => 3,
            'backorder' => null,
        ]);

        $this->assertTrue($product->isOnStock());
        $this->assertEquals(3, $product->onStockQuantity());
        $this->assertTrue($product->isBackorderUnrestricted());
        $this->assertNull($product->backorderQuantity());
        $this->assertEquals(3, $product->totalAvailableQuantity());

        $backOrderProduct = Product::create([
            'name' => 'Semperit 165/70 R 17',
            'sku' => 'SEMPED-GRIP4',
            'stock' => -1,
            'backorder' => 4,
        ]);

        $this->assertFalse($backOrderProduct->isOnStock());
        $this->assertEquals(-1, $backOrderProduct->onStockQuantity());
        $this->assertFalse($backOrderProduct->isBackorderUnrestricted());
        $this->assertEquals(4, $backOrderProduct->backorderQuantity());
        $this->assertEquals(3, $backOrderProduct->totalAvailableQuantity());
    }
}
