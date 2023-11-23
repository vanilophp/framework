<?php

declare(strict_types=1);

/**
 * Contains the MasterProductVariantStockTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-11-23
 *
 */

namespace Unit;

use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\MasterProduct\Models\MasterProductVariant;
use Vanilo\MasterProduct\Tests\TestCase;

class MasterProductVariantStockTest extends TestCase
{
    protected MasterProduct $master;

    protected function setUp(): void
    {
        parent::setUp();

        $this->master = MasterProduct::create(['name' => 'Yokka Magnitude Laptop']);
    }

    /** @test */
    public function the_stock_can_be_set()
    {
        $product = MasterProductVariant::create([
            'master_product_id' => $this->master->id,
            'name' => 'Yokka Magnitude YM34 Laptop',
            'sku' => '73781',
            'stock' => 50,
        ]);

        $this->assertEquals(50, $product->stock);
    }

    /** @test */
    public function the_stock_field_value_returns_a_numeric_value()
    {
        $createdProduct = MasterProductVariant::create([
            'master_product_id' => $this->master->id,
            'name' => 'Yokka Magnitude YM34 Laptop',
            'sku' => '73781',
            'stock' => 73.5,
        ]);

        $product = MasterProductVariant::find($createdProduct->id);

        $this->assertTrue(\is_numeric($product->stock));
    }

    /** @test */
    public function stock_field_value_defaults_to_zero()
    {
        $product = MasterProductVariant::create([
            'master_product_id' => $this->master->id,
            'name' => 'Yokka Magnitude YM34 Laptop',
            'sku' => '73781',
        ]);

        $this->assertEquals(0, $product->stock);
    }

    /** @test */
    public function is_on_stock_returns_false_if_the_stock_is_equal_to_zero()
    {
        $product = MasterProductVariant::create([
            'master_product_id' => $this->master->id,
            'name' => 'Yokka Magnitude YM34 Laptop',
            'sku' => '73781',
            'stock' => 0,
        ]);

        $this->assertFalse($product->isOnStock());
    }

    /** @test */
    public function is_on_stock_returns_false_if_the_stock_is_less_than_zero()
    {
        $product = MasterProductVariant::create([
            'master_product_id' => $this->master->id,
            'name' => 'Yokka Magnitude YM34 Laptop',
            'sku' => '73781',
            'stock' => -8,
        ]);

        $this->assertFalse($product->isOnStock());
    }

    /** @test */
    public function backorder_value_can_be_specified()
    {
        $product = MasterProductVariant::create([
            'master_product_id' => $this->master->id,
            'name' => 'Yokka Mokka Screen 14',
            'sku' => 'YMSCR1',
            'backorder' => 16,
        ]);

        $this->assertEquals(16, $product->backorder);
    }

    /** @test */
    public function backorder_is_null_by_default()
    {
        $product = MasterProductVariant::create([
            'master_product_id' => $this->master->id,
            'name' => 'Yokka Mokka Screen 15',
            'sku' => 'YMSCR2',
            'backorder' => null,
        ]);

        $this->assertNull($product->backorder);
    }

    /** @test */
    public function it_implements_the_stockable_interface()
    {
        $product = MasterProductVariant::create([
            'master_product_id' => $this->master->id,
            'name' => 'Yokka Mokka Screen 16',
            'sku' => 'YMSCR3',
            'stock' => 3,
            'backorder' => null,
        ]);

        $this->assertTrue($product->isOnStock());
        $this->assertEquals(3, $product->onStockQuantity());
        $this->assertTrue($product->isBackorderUnrestricted());
        $this->assertNull($product->backorderQuantity());
        $this->assertEquals(3, $product->totalAvailableQuantity());

        $backOrderProduct = MasterProductVariant::create([
            'master_product_id' => $this->master->id,
            'name' => 'Yokka Mokka Screen 17',
            'sku' => 'YMSCR4',
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
