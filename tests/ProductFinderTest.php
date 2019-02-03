<?php
/**
 * Contains the ProductFinderTest.php class.
 *
 * @copyright   Copyright (c) 2019 Attila Fulop
 * @author      Attila Fulop
 * @license     MIT
 * @since       2019-02-03
 *
 */

namespace Vanilo\Framework\Tests;

use Vanilo\Framework\Search\ProductFinder;
use Vanilo\Framework\Models\Product;

class ProductFinderTest extends TestCase
{
    /** @test */
    public function it_finds_a_product_by_exact_name()
    {
        factory(Product::class, 83)->create();
        factory(Product::class)->create([
            'name' => 'Shiny Glue'
        ]);

        $finder = new ProductFinder();
        $result = $finder->nameContains('Shiny Glue')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Shiny Glue', $first->name);
    }

    /** @test */
    public function it_finds_a_product_where_name_begins_with()
    {
        factory(Product::class, 35)->create();
        factory(Product::class)->create([
            'name' => 'Matured Cheese'
        ]);

        $finder = new ProductFinder();
        $result = $finder->nameStartsWith('Mature')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Matured Cheese', $first->name);
    }

    /** @test */
    public function it_finds_a_product_where_name_ends_with()
    {
        factory(Product::class, 27)->create();
        factory(Product::class)->create([
            'name' => 'Bobinated Transformator'
        ]);

        $finder = new ProductFinder();
        $result = $finder->nameEndsWith('Transformator')->getResults();

        $this->assertCount(1, $result);

        $first = $result->first();
        $this->assertInstanceOf(Product::class, $first);
        $this->assertEquals('Bobinated Transformator', $first->name);
    }

    /** @test */
    public function it_finds_multiple_results_where_name_contains_search_term()
    {
        factory(Product::class, 11)->create();
        factory(Product::class)->create(['name' => 'Mandarin As Language']);
        factory(Product::class)->create(['name' => 'Crazy Mandarins']);
        factory(Product::class)->create(['name' => 'Mandarin']);

        $finder = new ProductFinder();
        $this->assertCount(3, $finder->nameContains('Mandarin')->getResults());
    }

    /** @test */
    public function it_finds_multiple_results_where_name_starts_with_search_term()
    {
        factory(Product::class, 18)->create();
        factory(Product::class)->create(['name' => 'Orange Is Good']);
        factory(Product::class)->create(['name' => 'This Should Not Be Found']);
        factory(Product::class)->create(['name' => 'Oranges From Morocco']);

        $finder = new ProductFinder();
        $this->assertCount(2, $finder->nameStartsWith('Orange')->getResults());
    }

    /** @test */
    public function it_finds_multiple_results_where_name_ends_with_search_term()
    {
        factory(Product::class, 7)->create();
        factory(Product::class)->create(['name' => 'Awesome Blueberries']);
        factory(Product::class)->create(['name' => 'Blueberries Not Here']);
        factory(Product::class)->create(['name' => 'Blueberries']);
        factory(Product::class)->create(['name' => 'Vanilla + Blueberries']);

        $finder = new ProductFinder();
        $this->assertCount(3, $finder->nameEndsWith('Blueberries')->getResults());
    }

    /** @test */
    public function name_based_finders_can_be_combined()
    {
        factory(Product::class, 21)->create();
        factory(Product::class)->create(['name' => 'Waka Time']);
        factory(Product::class)->create(['name' => 'Kaka Waka']);
        factory(Product::class)->create(['name' => 'Tugo Waka Batagang']);

        $finder = new ProductFinder();
        $result = $finder
                    ->nameEndsWith('Waka')
                    ->nameStartsWith('Waka')
                    ->getResults();

        $this->assertCount(2, $result);
    }
}
