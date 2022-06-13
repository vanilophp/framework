<?php

declare(strict_types=1);

/**
 * Contains the BaseMasterProductAttributesTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-13
 *
 */

namespace Vanilo\MasterProduct\Tests\Unit;

use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\MasterProduct\Models\MasterProductProxy;
use Vanilo\MasterProduct\Tests\TestCase;

class BaseMasterProductAttributesTest extends TestCase
{
    /** @test */
    public function product_can_be_created_with_minimal_data()
    {
        $product = MasterProduct::create([
            'name' => 'Dell XPS 13 Laptop',
        ]);

        $this->assertNotNull($product->id);
        $this->assertEquals('Dell XPS 13 Laptop', $product->name);
    }

    /** @test */
    public function product_can_be_created_via_its_proxy()
    {
        $product = MasterProductProxy::create([
            'name' => 'Dell XPS 15',
        ]);

        $this->assertNotNull($product->id);
        $this->assertEquals('Dell XPS 15', $product->name);
    }

    /** @test */
    public function all_fields_can_be_properly_set()
    {
        $product = MasterProduct::create([
            'name' => 'Maxi Kukac 2020',
            'slug' => 'maxi-kukac-2020',
            'price' => 79.99,
            'original_price' => 94.99,
            'excerpt' => 'Maxi Kukac 2020 is the THING you always have dreamt of',
            'description' => 'Maxi Kukac 2020 makes your dreams come to reality',
            'state' => 'active',
            'meta_keywords' => 'maxi, kukac, dreams',
            'meta_description' => 'The THING you always have dreamt of'
        ]);

        $this->assertGreaterThanOrEqual(1, $product->id);
        $this->assertEquals('Maxi Kukac 2020', $product->name);
        $this->assertEquals(79.99, $product->price);
        $this->assertEquals(94.99, $product->original_price);
        $this->assertEquals('maxi-kukac-2020', $product->slug);
        $this->assertEquals('Maxi Kukac 2020 is the THING you always have dreamt of', $product->excerpt);
        $this->assertEquals('Maxi Kukac 2020 makes your dreams come to reality', $product->description);
        $this->assertEquals('active', $product->state->value());
        $this->assertEquals('maxi, kukac, dreams', $product->meta_keywords);
        $this->assertEquals('The THING you always have dreamt of', $product->meta_description);
    }

    /** @test */
    public function the_title_method_returns_name_if_no_ext_title_was_set()
    {
        $product = MasterProduct::create([
            'name' => 'Hello What?',
        ]);

        $this->assertEquals('Hello What?', $product->title());
    }

    /** @test */
    public function the_title_method_returns_the_title_if_the_field_is_set()
    {
        $product = MasterProduct::create([
            'name' => 'Hello Why?',
            'ext_title' => 'Buy the book Hello Why? with discount',
        ]);

        $this->assertEquals('Buy the book Hello Why? with discount', $product->title());
    }

    /** @test */
    public function the_title_can_be_returned_via_property_returns_as_well()
    {
        $productWithTitle = MasterProduct::create([
            'name' => 'Hello When?',
            'ext_title' => 'Buy Kitty Kats',
        ]);

        $productWithoutTitle = MasterProduct::create([
            'name' => 'Hello Where?',
        ]);

        $this->assertEquals('Buy Kitty Kats', $productWithTitle->title);
        $this->assertEquals($productWithTitle->title(), $productWithTitle->title);

        $this->assertEquals('Hello Where?', $productWithoutTitle->title);
        $this->assertEquals($productWithoutTitle->title(), $productWithoutTitle->title);
    }

    /** @test */
    public function the_id_field_is_an_integer()
    {
        $product = MasterProduct::create(['name' => 'Hey Hello']);

        $this->assertIsInt($product->id);
        $this->assertIsInt($product->fresh()->id);
    }
}
