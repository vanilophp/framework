<?php

declare(strict_types=1);

/**
 * Contains the MasterProductTest class.
 *
 * @copyright   Copyright (c) 2022 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2022-06-13
 *
 */

namespace Vanilo\MasterProduct\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Vanilo\Contracts\Buyable;
use Vanilo\MasterProduct\Models\MasterProduct;
use Vanilo\MasterProduct\Models\MasterProductProxy;
use Vanilo\MasterProduct\Tests\TestCase;

class MasterProductTest extends TestCase
{
    #[Test] public function product_can_be_created_with_minimal_data()
    {
        $product = MasterProduct::create([
            'name' => 'Dell XPS 13 Laptop',
        ]);

        $this->assertNotNull($product->id);
        $this->assertEquals('Dell XPS 13 Laptop', $product->name);
    }

    #[Test] public function product_can_be_created_via_its_proxy()
    {
        $product = MasterProductProxy::create([
            'name' => 'Dell XPS 15',
        ]);

        $this->assertNotNull($product->id);
        $this->assertEquals('Dell XPS 15', $product->name);
    }

    #[Test] public function all_fields_can_be_properly_set()
    {
        $product = MasterProduct::create([
            'name' => 'Maxi Kukac 2020',
            'slug' => 'maxi-kukac-2020',
            'price' => 79.99,
            'original_price' => 94.99,
            'priority' => 10,
            'subtitle' => 'Now, this is your mega-worm ever wanted',
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
        $this->assertEquals(10, $product->priority);
        $this->assertEquals('Now, this is your mega-worm ever wanted', $product->subtitle);
        $this->assertEquals('maxi-kukac-2020', $product->slug);
        $this->assertEquals('Maxi Kukac 2020 is the THING you always have dreamt of', $product->excerpt);
        $this->assertEquals('Maxi Kukac 2020 makes your dreams come to reality', $product->description);
        $this->assertEquals('active', $product->state->value());
        $this->assertEquals('maxi, kukac, dreams', $product->meta_keywords);
        $this->assertEquals('The THING you always have dreamt of', $product->meta_description);
    }

    #[Test] public function the_title_method_returns_name_if_no_ext_title_was_set()
    {
        $product = MasterProduct::create([
            'name' => 'Hello What?',
        ]);

        $this->assertEquals('Hello What?', $product->title());
    }

    #[Test] public function the_title_method_returns_the_title_if_the_field_is_set()
    {
        $product = MasterProduct::create([
            'name' => 'Hello Why?',
            'ext_title' => 'Buy the book Hello Why? with discount',
        ]);

        $this->assertEquals('Buy the book Hello Why? with discount', $product->title());
    }

    #[Test] public function the_title_can_be_returned_via_property_returns_as_well()
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

    #[Test] public function the_id_field_is_an_integer()
    {
        $product = MasterProduct::create(['name' => 'Hey Hello']);

        $this->assertIsInt($product->id);
        $this->assertIsInt($product->fresh()->id);
    }

    #[Test] public function the_master_product_does_not_have_an_sku()
    {
        $product = MasterProduct::create(['name' => 'Fish Pizza', 'sku' => 'FP-123']);

        $this->assertNull($product->sku);
    }

    #[Test] public function the_master_product_does_not_have_sales_fields()
    {
        $product = MasterProduct::create([
            'name' => 'Kalamari Pizza',
            'units_sold' => 12,
            'last_sale_at' => '2022-06-11 12:00:00',
        ]);

        $this->assertNull($product->units_sold);
        $this->assertNull($product->last_sale_at);
    }

    #[Test] public function the_master_product_is_not_buyable()
    {
        $product = MasterProduct::create([
            'name' => 'Kalamari Pizza',
        ]);

        $this->assertNotInstanceOf(Buyable::class, $product);
        $this->assertFalse(method_exists($product, 'addSale'));
        $this->assertFalse(method_exists($product, 'removeSale'));
    }

    #[Test] public function it_has_an_actives_query_builder_scope()
    {
        MasterProduct::factory()->active()->count(4)->create();
        MasterProduct::factory()->inactive()->count(7)->create();

        $this->assertCount(4, MasterProduct::actives()->get());
        $this->assertCount(7, MasterProduct::inactives()->get());
    }

    #[Test] public function the_subtitle_field_is_nullable(): void
    {
        $product = MasterProduct::create([
            'name' => 'Emirates',
            'sku' => 'MRTS4',
            'subtitle' => 'Rich Desert Experiences',
        ]);

        $product->fresh();
        $this->assertEquals('Rich Desert Experiences', $product->subtitle);

        $product->update([
            'subtitle' => null,
        ]);

        $product->fresh();

        $this->assertNull($product->subtitle);
    }

    #[Test] public function they_can_be_sorted_by_priority()
    {
        MasterProduct::factory()->active()->create(['name' => 'Underdog', 'priority' => -10]);
        MasterProduct::factory()->active()->create(['name' => 'Plain Jane']);
        MasterProduct::factory()->active()->create(['name' => 'Cash Cow', 'priority' => 10]);

        $products = MasterProduct::actives()->orderBy('priority', 'desc')->get();

        $this->assertCount(3, $products);

        $cashCow = $products->first();
        $this->assertEquals('Cash Cow', $cashCow->name);
        $this->assertEquals(10, $cashCow->priority);

        $underdog = $products->last();
        $this->assertEquals('Underdog', $underdog->name);
        $this->assertEquals(-10, $underdog->priority);

        $plainJane = $products->get(1);
        $this->assertEquals('Plain Jane', $plainJane->name);
        $this->assertEquals(0, $plainJane->priority);
    }
}
