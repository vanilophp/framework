<?php

declare(strict_types=1);

/**
 * Contains the ProductPriceTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-02-28
 *
 */

namespace Vanilo\Product\Tests;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Product\Models\Product;

class ProductPriceTest extends TestCase
{
    #[Test] public function the_price_field_is_null_by_default()
    {
        $product = Product::create(['sku' => 'FREEUA!', 'name' => 'Slava Ukraini!'])->fresh();

        $this->assertNull($product->price);
    }

    #[Test] public function the_price_field_is_a_float_if_it_has_a_value()
    {
        $product = Product::create(['sku' => 'SLUA!', 'name' => 'Glory To Ukraine!', 'price' => 2021.99])->fresh();

        $this->assertIsFloat($product->price);
        $this->assertEquals(2021.99, $product->price);
    }

    #[Test] public function the_original_price_field_is_null_by_default()
    {
        $product = Product::create(['sku' => 'XXX', 'name' => 'Some Product'])->fresh();

        $this->assertNull($product->original_price);
    }

    #[Test] public function the_original_price_field_is_a_float_if_it_has_a_value()
    {
        $product = Product::create(['sku' => 'XYZ', 'name' => 'Some Product', 'original_price' => 79.99])->fresh();

        $this->assertIsFloat($product->original_price);
        $this->assertEquals(79.99, $product->original_price);
    }
}
