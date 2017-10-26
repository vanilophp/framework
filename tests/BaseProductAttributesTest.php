<?php
/**
 * Contains the BaseProductAttributesTest class.
 *
 * @copyright   Copyright (c) 2017 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2017-10-07
 *
 */


namespace Konekt\Product\Tests;

use Konekt\Product\Models\Product;
use Konekt\Product\Models\ProductProxy;

class BaseProductAttributesTest extends TestCase
{
    /**
     * @test
     */
    public function product_can_be_created_with_minimal_data()
    {
        $product = Product::create([
            'name' => 'Dell Latitude E7240 Laptop',
            'sku'  => 'DLL-74237'
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
            'sku'  => 'DLL-74234'
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
            'name'             => 'Maxi Baxi 2000',
            'sku'              => 'MXB-2000',
            'slug'             => 'maxi-baxi-2000',
            'excerpt'          => 'Maxi Baxi 2000 is the THING you always have dreamt of',
            'description'      => 'Maxi Baxi 2000 makes your dreams come true. See: https://youtu.be/5RKM_VLEbOc',
            'state'            => 'active',
            'meta_keywords'    => 'maxi, baxi, dreams',
            'meta_description' => 'The THING you always have dreamt of'
        ]);

        $this->assertGreaterThanOrEqual(1, $product->id);
        $this->assertEquals('Maxi Baxi 2000', $product->name);
        $this->assertEquals('MXB-2000', $product->sku);
        $this->assertEquals('maxi-baxi-2000', $product->slug);
        $this->assertEquals('Maxi Baxi 2000 is the THING you always have dreamt of', $product->excerpt);
        $this->assertEquals('Maxi Baxi 2000 makes your dreams come true. See: https://youtu.be/5RKM_VLEbOc', $product->description);
        $this->assertEquals('active', $product->state->value());
        $this->assertEquals('maxi, baxi, dreams', $product->meta_keywords);
        $this->assertEquals('The THING you always have dreamt of', $product->meta_description);
    }


    /**
     * @test
     */
    public function the_title_method_returns_name_if_no_title_was_set()
    {
        $product = Product::create([
            'name' => 'Hello What?'
        ]);

        $this->assertEquals('Hello What?', $product->title());
    }

    /**
     * @test
     */
    public function the_title_method_returns_the_title_if_the_field_is_set()
    {
        $product = Product::create([
            'name'  => 'Hello Why?',
            'title' => 'Buy the book Hello Why? with discount'
        ]);

        $this->assertEquals('Buy the book Hello Why? with discount', $product->title());

    }
}
