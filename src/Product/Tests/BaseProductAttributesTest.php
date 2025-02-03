<?php

declare(strict_types=1);

/**
 * Contains the BaseProductAttributesTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */

namespace Vanilo\Product\Tests;

use Vanilo\Product\Models\Product;
use Vanilo\Product\Models\ProductProxy;

class BaseProductAttributesTest extends TestCase
{
    /**
     * @test
     */
    public function product_can_be_created_with_minimal_data()
    {
        $product = Product::create([
            'name' => 'Dell Latitude E7240 Laptop',
            'sku' => 'DLL-74237'
        ]);

        $this->assertNotNull($product->id);
        $this->assertEquals('Dell Latitude E7240 Laptop', $product->name);
        $this->assertEquals('DLL-74237', $product->sku);
    }

    /**
     * @test
     */
    public function product_can_be_created_via_its_proxy()
    {
        $product = ProductProxy::create([
            'name' => 'Dell Latitude E7440 Laptop',
            'sku' => 'DLL-74234'
        ]);

        $this->assertNotNull($product->id);
        $this->assertEquals('Dell Latitude E7440 Laptop', $product->name);
        $this->assertEquals('DLL-74234', $product->sku);
    }

    /**
     * @test
     */
    public function all_fields_can_be_properly_set()
    {
        $product = Product::create([
            'name' => 'Maxi Baxi 2000',
            'sku' => 'MXB-2000',
            'stock' => 123.4567,
            'slug' => 'maxi-baxi-2000',
            'excerpt' => 'Maxi Baxi 2000 is the THING you always have dreamt of',
            'description' => 'Maxi Baxi 2000 makes your dreams come true. See: https://youtu.be/5RKM_VLEbOc',
            'state' => 'active',
            'meta_keywords' => 'maxi, baxi, dreams',
            'meta_description' => 'The THING you always have dreamt of',
            'gtin' => '8675309',
        ]);

        $this->assertGreaterThanOrEqual(1, $product->id);
        $this->assertEquals('Maxi Baxi 2000', $product->name);
        $this->assertEquals('MXB-2000', $product->sku);
        $this->assertEquals(123.4567, $product->stock);
        $this->assertTrue($product->isOnStock());
        $this->assertEquals('maxi-baxi-2000', $product->slug);
        $this->assertEquals('Maxi Baxi 2000 is the THING you always have dreamt of', $product->excerpt);
        $this->assertEquals(
            'Maxi Baxi 2000 makes your dreams come true. See: https://youtu.be/5RKM_VLEbOc',
            $product->description
        );
        $this->assertEquals('active', $product->state->value());
        $this->assertEquals('maxi, baxi, dreams', $product->meta_keywords);
        $this->assertEquals('The THING you always have dreamt of', $product->meta_description);
        $this->assertEquals('8675309', $product->gtin);
    }

    /**
     * @test
     */
    public function the_title_method_returns_name_if_no_title_was_set()
    {
        $product = Product::create([
            'name' => 'Hello What?',
            'sku' => 'NEEDED-1'
        ]);

        $this->assertEquals('Hello What?', $product->title());
    }

    /**
     * @test
     */
    public function the_title_method_returns_the_title_if_the_field_is_set()
    {
        $product = Product::create([
            'name' => 'Hello Why?',
            'sku' => 'NEEDED-2',
            'ext_title' => 'Buy the book Hello Why? with discount'
        ]);

        $this->assertEquals('Buy the book Hello Why? with discount', $product->title());
    }

    /**
     * @test
     */
    public function the_title_can_be_returned_via_property_returns_as_well()
    {
        $productWithTitle = Product::create([
            'name' => 'Hello When?',
            'sku' => 'NEEDED-3',
            'ext_title' => 'Buy Kitty Kats'
        ]);

        $productWithoutTitle = Product::create([
            'name' => 'Hello Where?',
            'sku' => 'NEEDED-4'
        ]);

        $this->assertEquals('Buy Kitty Kats', $productWithTitle->title);
        $this->assertEquals($productWithTitle->title(), $productWithTitle->title);

        $this->assertEquals('Hello Where?', $productWithoutTitle->title);
        $this->assertEquals($productWithoutTitle->title(), $productWithoutTitle->title);
    }

    /** @test */
    public function the_id_field_is_an_integer()
    {
        $product = Product::create(['sku' => 'AAA', 'name' => 'Hey Hello']);

        $this->assertIsInt($product->id);
        $this->assertIsInt($product->fresh()->id);
    }

    /** @test */
    public function product_can_be_retrieved_by_sku()
    {
        ProductProxy::create([
            'name' => 'Dell XPS 13 Developer Edition',
            'sku' => 'DLL-XPS13DE'
        ]);

        $product = Product::findBySku('DLL-XPS13DE');
        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('Dell XPS 13 Developer Edition', $product->name);
        $this->assertEquals('DLL-XPS13DE', $product->sku);
    }

    /** @test */
    public function find_by_sku_returns_null_if_no_product_was_found_by_the_requested_sku()
    {
        $this->assertNull(Product::findBySku('Oh man such SKU could not exist in the Wild. Nor in a lab'));
    }

    /** @test */
    public function the_product_gtin_field_is_nullable(): void
    {
        $product = Product::create([
            'name' => 'Kosmodisk',
            'sku' => 'kosmodisk',
            'gtin' => '9876543210',
        ]);

        $product->update([
            'gtin' => null,
        ]);

        $product->fresh();

        $this->assertNull($product->gtin);
    }
}
