<?php

declare(strict_types=1);

/**
 * Contains the TaxesTest class.
 *
 * @copyright   Copyright (c) 2023 Vanilo UG
 * @author      Attila Fulop
 * @license     MIT
 * @since       2023-03-25
 *
 */

namespace Vanilo\Foundation\Tests;

use Vanilo\Foundation\Models\MasterProduct;
use Vanilo\Foundation\Models\MasterProductVariant;
use Vanilo\Foundation\Models\Product;
use Vanilo\Taxes\Models\TaxCategory;

class TaxesTest extends TestCase
{
    /** @test */
    public function the_product_model_can_belong_to_a_tax_category()
    {
        $taxCategory = TaxCategory::create(['name' => 'Standard']);
        $product = factory(Product::class)->create([
            'tax_category_id' => $taxCategory->id,
        ]);

        $this->assertInstanceOf(TaxCategory::class, $product->taxCategory);
        $this->assertEquals($taxCategory->id, $product->taxCategory->id);
    }

    /** @test */
    public function the_master_product_model_can_belong_to_a_tax_category()
    {
        $taxCategory = TaxCategory::create(['name' => 'Standard']);
        $product = factory(MasterProduct::class)->create([
            'tax_category_id' => $taxCategory->id,
        ]);

        $this->assertInstanceOf(TaxCategory::class, $product->taxCategory);
        $this->assertEquals($taxCategory->id, $product->taxCategory->id);
    }

    /** @test */
    public function the_master_product_variant_model_can_belong_to_a_tax_category()
    {
        $taxCategory = TaxCategory::create(['name' => 'Standard']);
        $product = factory(MasterProductVariant::class)->create([
            'tax_category_id' => $taxCategory->id,
        ]);

        $this->assertInstanceOf(TaxCategory::class, $product->taxCategory);
        $this->assertEquals($taxCategory->id, $product->taxCategory->id);
    }
}
